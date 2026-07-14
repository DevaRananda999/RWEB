@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">🪑</div>
        <div class="stat-info">
            <h3>Meja Tersedia</h3>
            <div class="stat-value">{{ $totalMejaTersedia }}/{{ $totalMeja }}</div>
            <div class="stat-desc">Meja siap digunakan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">📋</div>
        <div class="stat-info">
            <h3>Order Aktif</h3>
            <div class="stat-value">{{ $orderAktif }}</div>
            <div class="stat-desc">Sedang diproses</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon gold">💰</div>
        <div class="stat-info">
            <h3>Pendapatan Hari Ini</h3>
            <div class="stat-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="stat-desc">Total pemasukan hari ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">📅</div>
        <div class="stat-info">
            <h3>Reservasi Hari Ini</h3>
            <div class="stat-value">{{ $reservasiHariIni->count() }}</div>
            <div class="stat-desc">Jadwal tamu hari ini</div>
        </div>
    </div>
</div>

<div class="grid-2">
    {{-- Order Terbaru --}}
    <div class="card">
        <div class="card-header">
            <h3>📋 Order Terbaru</h3>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-gold">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Meja</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderTerbaru as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}" class="fw-bold">{{ $order->kode_order }}</a></td>
                            <td>{{ $order->meja->nomor_meja ?? '-' }}</td>
                            <td>{{ $order->kasir->name ?? '-' }}</td>
                            <td class="text-gold fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status === 'diproses')
                                    <span class="badge badge-info">Diproses</span>
                                @elseif($order->status === 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($order->status === 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 32px;">Belum ada order</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Info --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Menu Terlaris --}}
        <div class="card">
            <div class="card-header">
                <h3>🏆 Menu Terlaris</h3>
            </div>
            <div class="card-body">
                @forelse($menuTerlaris as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--color-border);">
                        <span style="font-weight: 500;">{{ $item->menu->nama_menu ?? 'Menu terhapus' }}</span>
                        <span class="badge badge-gold">{{ $item->total_terjual }} terjual</span>
                    </div>
                @empty
                    <p class="text-muted text-center" style="padding: 20px 0;">Belum ada data penjualan</p>
                @endforelse
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="card">
            <div class="card-header">
                <h3>⚠️ Stok Menipis</h3>
            </div>
            <div class="card-body">
                @forelse($menuStokMenipis as $menu)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--color-border);">
                        <span style="font-weight: 500;">{{ $menu->nama_menu }}</span>
                        <span class="badge badge-danger">Sisa {{ $menu->stok }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center" style="padding: 20px 0;">Semua stok aman 👍</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Reservasi Hari Ini --}}
@if($reservasiHariIni->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <h3>📅 Reservasi Hari Ini</h3>
        <a href="{{ route('reservasis.index') }}" class="btn btn-sm btn-outline-gold">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama</th>
                        <th>Meja</th>
                        <th>Jumlah Tamu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasiHariIni as $r)
                    <tr>
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($r->waktu_reservasi)->format('H:i') }}</td>
                        <td>{{ $r->nama_pemesan }}</td>
                        <td>{{ $r->meja->nomor_meja ?? '-' }}</td>
                        <td>{{ $r->jumlah_tamu }} orang</td>
                        <td>
                            @if($r->status === 'dikonfirmasi')
                                <span class="badge badge-success">Dikonfirmasi</span>
                            @elseif($r->status === 'menunggu')
                                <span class="badge badge-warning">Menunggu</span>
                            @else
                                <span class="badge badge-info">{{ ucfirst($r->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
