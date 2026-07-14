@extends('layouts.app')

@section('title', 'Checkout — ' . $order->kode_order)
@section('page-title', 'Proses Pembayaran')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>💳 Checkout — {{ $order->kode_order }}</h2>
    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">← Kembali ke Order</a>
</div>

<div class="checkout-grid">
    {{-- LEFT: Ringkasan Order --}}
    <div class="card">
        <div class="card-header">
            <h3>🧾 Ringkasan Order</h3>
            <span class="badge badge-gold">Meja {{ $order->meja->nomor_meja }}</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->detailPesanans as $item)
                        <tr>
                            <td>{{ $item->menu->nama_menu }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td class="text-gold fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right fw-bold" style="font-size: 1.1rem; padding: 16px;">TOTAL</td>
                            <td class="text-gold fw-bold" style="font-size: 1.3rem; padding: 16px;">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT: Form Pembayaran --}}
    <div class="card">
        <div class="card-header">
            <h3>💰 Pembayaran</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayarans.store', $order) }}" method="POST" id="paymentForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Total yang Harus Dibayar</label>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800; color: var(--color-gold); padding: 12px 0;">
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                        <option value="">— Pilih Metode —</option>
                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
                        <option value="kartu_debit" {{ old('metode_pembayaran') == 'kartu_debit' ? 'selected' : '' }}>💳 Kartu Debit</option>
                        <option value="kartu_kredit" {{ old('metode_pembayaran') == 'kartu_kredit' ? 'selected' : '' }}>💳 Kartu Kredit</option>
                        <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                    </select>
                    @error('metode_pembayaran') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jumlah_bayar">Jumlah Bayar (Rp)</label>
                    <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-control"
                           min="{{ $order->total_harga }}" step="1000"
                           value="{{ old('jumlah_bayar', $order->total_harga) }}" required
                           oninput="hitungKembalian()">
                    @error('jumlah_bayar') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- Quick amount buttons for cash --}}
                <div class="d-flex gap-1 mb-3" style="flex-wrap: wrap;" id="quickAmounts">
                    @php
                        $total = $order->total_harga;
                        $amounts = [
                            ceil($total / 10000) * 10000,
                            ceil($total / 50000) * 50000,
                            ceil($total / 100000) * 100000,
                        ];
                        $amounts = array_unique($amounts);
                    @endphp
                    @foreach($amounts as $amount)
                        <button type="button" class="btn btn-sm btn-outline-gold"
                                onclick="document.getElementById('jumlah_bayar').value={{ $amount }};hitungKembalian();">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="form-label">Kembalian</label>
                    <div id="kembalianDisplay" style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--color-success); padding: 8px 0;">
                        Rp 0
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg btn-block"
                        onclick="return confirm('Proses pembayaran? Pastikan jumlah bayar sudah benar.')">
                    ✅ Proses Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const totalHarga = {{ $order->total_harga }};

    function hitungKembalian() {
        const bayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
        const kembalian = Math.max(0, bayar - totalHarga);
        document.getElementById('kembalianDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
    }

    hitungKembalian();
</script>
@endpush
@endsection
