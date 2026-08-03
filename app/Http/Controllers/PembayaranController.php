<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pembayaran::with(['order.meja', 'order.kasir']);

        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->string('metode'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('dibayar_pada', $request->string('tanggal'));
        }

        $pembayarans = $query->latest()->paginate(10)->withQueryString();

        $totalHariIni = Pembayaran::where('status', 'lunas')
            ->whereDate('dibayar_pada', today())
            ->sum('jumlah_bayar');

        return view('pembayarans.index', compact('pembayarans', 'totalHariIni'));
    }

    /**
     * Halaman checkout — form pembayaran untuk order tertentu.
     */
    public function checkout(Order $order): View
    {
        if ($order->pembayaran) {
            return view('pembayarans.struk', [
                'pembayaran' => $order->pembayaran->load('order.meja', 'order.kasir', 'order.detailPesanans.menu'),
            ]);
        }

        $order->load(['meja', 'kasir', 'detailPesanans.menu']);

        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->kode_order . '-' . time(),
                'gross_amount' => $order->total_harga,
            ),
            'customer_details' => array(
                'first_name' => 'Meja ' . ($order->meja->nomor_meja ?? 'Unknown'),
            ),
            'enabled_payments' => ['gopay', 'shopeepay', 'other_qris'],
        );

        $snapToken = '';
        try {
            if (env('MIDTRANS_SERVER_KEY')) {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            }
        } catch (\Exception $e) {
            // Handle error quietly or log it
        }

        return view('pembayarans.checkout', compact('order', 'snapToken'));
    }

    /**
     * Proses pembayaran: simpan data, update status order dan meja.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        if ($order->pembayaran) {
            return redirect()->route('pembayarans.struk', $order)->with('error', 'Order ini sudah dibayar.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:qris,tunai'],
            'jumlah_bayar' => ['required_if:metode_pembayaran,tunai', 'nullable', 'numeric', 'min:'.$order->total_harga],
        ]);

        // Untuk QRIS, jumlah bayar = total harga (pas, tanpa kembalian)
        if ($validated['metode_pembayaran'] === 'qris') {
            $validated['jumlah_bayar'] = $order->total_harga;
        }

        DB::transaction(function () use ($validated, $order) {
            $kembalian = $validated['jumlah_bayar'] - $order->total_harga;

            Pembayaran::create([
                'order_id' => $order->id,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'kembalian' => $kembalian,
                'status' => 'lunas',
                'dibayar_pada' => now(),
            ]);

            $order->update(['status' => 'selesai']);
            $order->meja()->update(['status' => 'available']);
        });

        return redirect()->route('pembayarans.struk', $order)->with('success', 'Pembayaran berhasil diproses!');
    }

    /**
     * Halaman cetak struk.
     */
    public function struk(Order $order): View
    {
        $pembayaran = $order->pembayaran;

        if (! $pembayaran) {
            abort(404, 'Pembayaran belum diproses.');
        }

        $pembayaran->load('order.meja', 'order.kasir', 'order.detailPesanans.menu');

        return view('pembayarans.struk', compact('pembayaran'));
    }
}
