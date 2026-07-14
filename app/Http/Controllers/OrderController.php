<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Daftar semua order (riwayat).
     */
    public function index(Request $request): View
    {
        $query = Order::with(['meja', 'kasir', 'pembayaran']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Layar POS untuk membuat order baru: pilih meja lalu susun pesanan.
     */
    public function create(Request $request): View
    {
        $mejas = Meja::orderBy('nomor_meja')->get();
        $menus = Menu::where('tersedia', true)->orderBy('kategori')->orderBy('nama_menu')->get();

        $mejaTerpilih = $request->filled('meja_id')
            ? Meja::find($request->integer('meja_id'))
            : null;

        return view('orders.create', compact('mejas', 'menus', 'mejaTerpilih'));
    }

    /**
     * Simpan order baru beserta item pesanan, kurangi stok, tandai meja terisi.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meja_id' => ['required', 'exists:mejas,id'],
            'catatan' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $meja = Meja::findOrFail($validated['meja_id']);

        if ($meja->status === 'occupied') {
            return back()->with('error', 'Meja tersebut sedang terisi order lain.')->withInput();
        }

        try {
            $order = DB::transaction(function () use ($validated, $meja, $request) {
                $order = Order::create([
                    'kode_order' => Order::generateKodeOrder(),
                    'meja_id' => $meja->id,
                    'user_id' => $request->user()->id,
                    'status' => 'diproses',
                    'catatan' => $validated['catatan'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    $menu = Menu::lockForUpdate()->findOrFail($item['menu_id']);

                    if ($menu->stok < $item['jumlah']) {
                        throw new \RuntimeException("Stok {$menu->nama_menu} tidak mencukupi (sisa {$menu->stok}).");
                    }

                    DetailPesanan::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $menu->harga,
                        'subtotal' => $menu->harga * $item['jumlah'],
                    ]);

                    $menu->decrement('stok', $item['jumlah']);
                }

                $order->refreshTotal();
                $meja->update(['status' => 'occupied']);

                return $order;
            });

            return redirect()->route('orders.show', $order)->with('success', 'Order berhasil dibuat: '.$order->kode_order);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Detail order (layar POS aktif): tambah item, ubah status, menuju pembayaran.
     */
    public function show(Order $order): View
    {
        $order->load(['meja', 'kasir', 'detailPesanans.menu', 'pembayaran']);
        $menus = Menu::where('tersedia', true)->orderBy('kategori')->orderBy('nama_menu')->get();

        return view('orders.show', compact('order', 'menus'));
    }

    /**
     * Tambah item ke order yang sedang berjalan.
     */
    public function addItem(Request $request, Order $order): RedirectResponse
    {
        if (in_array($order->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Order sudah ditutup, tidak bisa menambah item.');
        }

        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::transaction(function () use ($validated, $order) {
                $menu = Menu::lockForUpdate()->findOrFail($validated['menu_id']);

                if ($menu->stok < $validated['jumlah']) {
                    throw new \RuntimeException("Stok {$menu->nama_menu} tidak mencukupi (sisa {$menu->stok}).");
                }

                DetailPesanan::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $validated['jumlah'],
                    'harga_satuan' => $menu->harga,
                    'subtotal' => $menu->harga * $validated['jumlah'],
                ]);

                $menu->decrement('stok', $validated['jumlah']);
                $order->refreshTotal();
            });

            return back()->with('success', 'Item berhasil ditambahkan ke order.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus item dari order dan kembalikan stok.
     */
    public function removeItem(Order $order, DetailPesanan $item): RedirectResponse
    {
        if (in_array($order->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Order sudah ditutup, tidak bisa mengubah item.');
        }

        DB::transaction(function () use ($order, $item) {
            $item->menu()->increment('stok', $item->jumlah);
            $item->delete();
            $order->refreshTotal();
        });

        return back()->with('success', 'Item berhasil dihapus dari order.');
    }

    /**
     * Perbarui status order (pending/diproses/selesai/dibatalkan) dan status meja.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diproses,selesai,dibatalkan'],
        ]);

        try {
            DB::transaction(function () use ($validated, $order) {
                if ($validated['status'] === 'dibatalkan' && $order->status !== 'dibatalkan') {
                    foreach ($order->detailPesanans as $item) {
                        $item->menu()->increment('stok', $item->jumlah);
                    }
                    $order->meja()->update(['status' => 'available']);
                }

                if ($validated['status'] === 'selesai' && $order->pembayaran()->doesntExist()) {
                    throw new \RuntimeException('Order belum bisa diselesaikan sebelum pembayaran diproses.');
                }

                $order->update(['status' => $validated['status']]);

                if ($validated['status'] === 'selesai') {
                    $order->meja()->update(['status' => 'available']);
                }
            });

            return back()->with('success', 'Status order berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
