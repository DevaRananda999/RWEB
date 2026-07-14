@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')
@section('page-title', 'Riwayat Pembayaran')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>💰 Riwayat Pembayaran</h2>
    <div class="stat-card" style="margin: 0; padding: 16px 24px;">
        <div class="stat-icon gold">💰</div>
        <div class="stat-info">
            <h3 style="font-size: 0.7rem;">Total Hari Ini</h3>
            <div class="stat-value" style="font-size: 1.3rem;">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form action="{{ route('pembayarans.index') }}" method="GET" class="d-flex gap-1 align-center" style="flex-wrap: wrap;">
        <select name="metode" class="form-control" style="max-width: 180px;">
            <option value="">Semua Metode</option>
            <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
            <option value="kartu_debit" {{ request('metode') == 'kartu_debit' ? 'selected' : '' }}>Kartu Debit</option>
            <option value="kartu_kredit" {{ request('metode') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
            <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="max-width: 180px;">
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request()->hasAny(['metode', 'tanggal']))
            <a href="{{ route('pembayarans.index') }}" class="btn btn-sm btn-outline-gold">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode Order</th>
                        <th>Meja</th>
                        <th>Kasir</th>
                        <th>Metode</th>
                        <th>Total Order</th>
                        <th>Dibayar</th>
                        <th>Kembalian</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $p)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $p->order) }}" class="fw-bold">{{ $p->order->kode_order }}</a>
                        </td>
                        <td>{{ $p->order->meja->nomor_meja ?? '-' }}</td>
                        <td>{{ $p->order->kasir->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-gold">{{ ucfirst(str_replace('_', ' ', $p->metode_pembayaran)) }}</span>
                        </td>
                        <td class="text-gold fw-bold">Rp {{ number_format($p->order->total_harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($p->kembalian, 0, ',', '.') }}</td>
                        <td class="text-muted" style="font-size: 0.8rem;">{{ $p->dibayar_pada?->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('pembayarans.struk', $p->order) }}" class="btn btn-sm btn-info">🧾 Struk</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 40px;">
                            Belum ada data pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 16px;">
    {{ $pembayarans->links() }}
</div>
@endsection
