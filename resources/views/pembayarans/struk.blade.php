@extends('layouts.app')

@section('title', 'Struk Pembayaran')
@section('page-title', 'Struk Pembayaran')

@section('content')
<div class="d-flex justify-between align-center mb-3 no-print">
    <h2>🧾 Struk Pembayaran</h2>
    <div class="btn-group">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

<div class="struk-container">
    <h2>🍽️ Fine Dining POS</h2>
    <p class="struk-subtitle">Jl. Contoh Restoran No. 123</p>

    <hr>

    <table>
        <tr>
            <td><strong>No. Order</strong></td>
            <td style="text-align: right;">{{ $pembayaran->order->kode_order }}</td>
        </tr>
        <tr>
            <td><strong>Meja</strong></td>
            <td style="text-align: right;">{{ $pembayaran->order->meja->nomor_meja ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kasir</strong></td>
            <td style="text-align: right;">{{ $pembayaran->order->kasir->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td style="text-align: right;">{{ $pembayaran->dibayar_pada?->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <hr>

    <table>
        <thead>
            <tr>
                <th style="text-align: left;">Item</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran->order->detailPesanans as $item)
            <tr>
                <td>{{ $item->menu->nama_menu }}</td>
                <td style="text-align: center;">{{ $item->jumlah }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td class="struk-total"><strong>TOTAL</strong></td>
            <td class="struk-total" style="text-align: right;"><strong>Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Metode</td>
            <td style="text-align: right;">{{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td style="text-align: right;">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kembalian</strong></td>
            <td style="text-align: right;"><strong>Rp {{ number_format($pembayaran->kembalian, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <hr>

    <div class="struk-footer">
        <p>Terima kasih telah berkunjung!</p>
        <p>Fine Dining POS &copy; {{ date('Y') }}</p>
    </div>
</div>
@endsection
