<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalMejaTersedia = Meja::tersedia()->count();
        $totalMeja = Meja::count();

        $orderAktif = Order::whereIn('status', ['pending', 'diproses'])->count();

        $pendapatanHariIni = Pembayaran::where('status', 'lunas')
            ->whereDate('dibayar_pada', today())
            ->sum('jumlah_bayar');

        $reservasiHariIni = Reservasi::whereDate('tanggal_reservasi', today())
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('waktu_reservasi')
            ->with('meja')
            ->get();

        $menuStokMenipis = Menu::where('stok', '<=', 5)->orderBy('stok')->get();

        $orderTerbaru = Order::with(['meja', 'kasir'])
            ->latest()
            ->take(8)
            ->get();

        $menuTerlaris = DetailPesanan::selectRaw('menu_id, SUM(jumlah) as total_terjual')
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', 'dibatalkan');
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalMejaTersedia',
            'totalMeja',
            'orderAktif',
            'pendapatanHariIni',
            'reservasiHariIni',
            'menuStokMenipis',
            'orderTerbaru',
            'menuTerlaris',
        ));
    }
}
