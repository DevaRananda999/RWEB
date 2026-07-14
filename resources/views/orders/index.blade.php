@extends('layouts.app')

@section('title', 'Daftar Order')
@section('page-title', 'Daftar Order')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>📋 Riwayat Order</h2>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">🛒 Order Baru</a>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form action="{{ route('orders.index') }}" method="GET" class="d-flex gap-1 align-center">
        <select name="status" class="form-control" style="max-width: 180px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request('status'))
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-gold">Reset</a>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="fw-bold">{{ $order->kode_order }}</a>
                        </td>
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
                        <td>
                            @if($order->pembayaran)
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning">Belum</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size: 0.8rem;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-secondary">👁️</a>
                                @if(!$order->pembayaran && in_array($order->status, ['pending', 'diproses']))
                                    <a href="{{ route('pembayarans.checkout', $order) }}" class="btn btn-sm btn-success">💳</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                            Belum ada data order.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 16px;">
    {{ $orders->links() }}
</div>
@endsection
