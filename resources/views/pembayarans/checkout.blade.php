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
            {{-- Total --}}
            <div class="form-group" style="text-align: center; margin-bottom: 20px;">
                <label class="form-label">Total yang Harus Dibayar</label>
                <div style="font-family: 'Outfit', sans-serif; font-size: 2.2rem; font-weight: 800; color: var(--color-gold); padding: 8px 0;">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </div>
            </div>

            {{-- Tab Buttons --}}
            <div style="display: flex; gap: 8px; margin-bottom: 20px;">
                <button type="button" class="btn btn-lg" id="tab-qris"
                        onclick="switchTab('qris')"
                        style="flex: 1; padding: 14px; font-size: 1rem; font-weight: 700; border: 2px solid var(--color-gold); background: var(--color-gold); color: #000; border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                    📱 QRIS
                </button>
                <button type="button" class="btn btn-lg" id="tab-tunai"
                        onclick="switchTab('tunai')"
                        style="flex: 1; padding: 14px; font-size: 1rem; font-weight: 700; border: 2px solid var(--color-gold); background: transparent; color: var(--color-gold); border-radius: 10px; cursor: pointer; transition: all 0.3s;">
                    💵 Tunai
                </button>
            </div>

            {{-- Panel QRIS --}}
            <div id="panel-qris" style="text-align: center;">
                @if(empty($snapToken))
                    <div class="alert alert-danger" style="border-radius: 10px; padding: 16px;">
                        ⚠️ Midtrans Server Key belum diatur di .env! QRIS tidak tersedia.
                    </div>
                @else
                    <div style="padding: 20px; background: rgba(255, 193, 7, 0.05); border: 1px dashed rgba(255, 193, 7, 0.3); border-radius: 12px; margin-bottom: 16px;">
                        <p style="margin-bottom: 12px; color: var(--color-text-secondary); font-size: 0.9rem;">
                            Klik tombol di bawah untuk memunculkan QR Code pembayaran
                        </p>
                        <button type="button" id="pay-button" class="btn btn-success btn-lg btn-block"
                                style="padding: 16px; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 10px; border-radius: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                            Bayar dengan QRIS
                        </button>
                    </div>
                    <form action="{{ route('pembayarans.store', $order) }}" method="POST" id="qrisForm" style="display: none;">
                        @csrf
                        <input type="hidden" name="metode_pembayaran" value="qris">
                    </form>
                @endif
            </div>

            {{-- Panel Tunai --}}
            <div id="panel-tunai" style="display: none;">
                <form action="{{ route('pembayarans.store', $order) }}" method="POST" id="tunaiForm">
                    @csrf
                    <input type="hidden" name="metode_pembayaran" value="tunai">

                    <div class="form-group">
                        <label class="form-label" for="jumlah_bayar">💵 Jumlah Uang Diterima (Rp)</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-control"
                               min="{{ $order->total_harga }}" step="1000"
                               value="{{ old('jumlah_bayar', $order->total_harga) }}" required
                               oninput="hitungKembalian()"
                               style="font-size: 1.3rem; padding: 12px; font-weight: 700; text-align: center;">
                        @error('jumlah_bayar') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Quick amount buttons --}}
                    <div class="d-flex gap-1 mb-3" style="flex-wrap: wrap;" id="quickAmounts">
                        @php
                            $total = $order->total_harga;
                            $amounts = [
                                $total,
                                ceil($total / 10000) * 10000,
                                ceil($total / 50000) * 50000,
                                ceil($total / 100000) * 100000,
                            ];
                            $amounts = array_unique($amounts);
                            sort($amounts);
                        @endphp
                        @foreach($amounts as $amount)
                            <button type="button" class="btn btn-sm btn-outline-gold"
                                    onclick="document.getElementById('jumlah_bayar').value={{ $amount }};hitungKembalian();"
                                    style="border-radius: 8px;">
                                Rp {{ number_format($amount, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>

                    <div class="form-group" style="background: rgba(40, 167, 69, 0.08); border-radius: 10px; padding: 16px; text-align: center;">
                        <label class="form-label" style="margin-bottom: 4px;">Kembalian</label>
                        <div id="kembalianDisplay" style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 700; color: var(--color-success);">
                            Rp 0
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block"
                            onclick="return confirm('Proses pembayaran tunai? Pastikan jumlah uang sudah benar.')"
                            style="padding: 16px; font-size: 1.1rem; border-radius: 12px; margin-top: 12px;">
                        ✅ Proses Pembayaran Tunai
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if(!empty($snapToken))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                document.getElementById('qrisForm').submit();
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                // Customer closed the popup without finishing the payment
            }
        });
    };
</script>
@endif
<script>
    const totalHarga = {{ $order->total_harga }};

    function switchTab(tab) {
        const qrisPanel = document.getElementById('panel-qris');
        const tunaiPanel = document.getElementById('panel-tunai');
        const qrisTab = document.getElementById('tab-qris');
        const tunaiTab = document.getElementById('tab-tunai');

        if (tab === 'qris') {
            qrisPanel.style.display = 'block';
            tunaiPanel.style.display = 'none';
            qrisTab.style.background = 'var(--color-gold)';
            qrisTab.style.color = '#000';
            tunaiTab.style.background = 'transparent';
            tunaiTab.style.color = 'var(--color-gold)';
        } else {
            qrisPanel.style.display = 'none';
            tunaiPanel.style.display = 'block';
            tunaiTab.style.background = 'var(--color-gold)';
            tunaiTab.style.color = '#000';
            qrisTab.style.background = 'transparent';
            qrisTab.style.color = 'var(--color-gold)';
        }
    }

    function hitungKembalian() {
        const bayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
        const kembalian = Math.max(0, bayar - totalHarga);
        document.getElementById('kembalianDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
    }

    hitungKembalian();
</script>
@endpush
@endsection

