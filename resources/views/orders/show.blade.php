@extends('layouts.app')

@section('title', 'Detail Order ' . $order->kode_order)
@section('page-title', 'Detail Order')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <div>
        <h2>📋 Order {{ $order->kode_order }}</h2>
        <p class="text-muted" style="font-size: 0.85rem;">
            Meja <strong>{{ $order->meja->nomor_meja }}</strong> • Kasir: {{ $order->kasir->name }} •
            {{ $order->created_at->format('d M Y, H:i') }}
        </p>
    </div>
    <div class="btn-group">
        @if(!$order->pembayaran && in_array($order->status, ['pending', 'diproses']))
            <a href="{{ route('pembayarans.checkout', $order) }}" class="btn btn-success">💳 Bayar</a>
        @endif
        @if($order->pembayaran)
            <a href="{{ route('pembayarans.struk', $order) }}" class="btn btn-info">🧾 Struk</a>
        @endif
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

<div class="pos-layout">
    {{-- LEFT: Detail Pesanan --}}
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h3>🍽️ Item Pesanan</h3>
                <div>
                    @if($order->status === 'diproses')
                        <span class="badge badge-info">Diproses</span>
                    @elseif($order->status === 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif($order->status === 'dibatalkan')
                        <span class="badge badge-danger">Dibatalkan</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                @if(in_array($order->status, ['pending', 'diproses']))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->detailPesanans as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->menu->nama_menu }}</td>
                                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td class="text-gold fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                @if(in_array($order->status, ['pending', 'diproses']))
                                <td>
                                    <form action="{{ route('orders.removeItem', [$order, $item]) }}" method="POST"
                                          onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 32px;">
                                    Belum ada item pesanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="{{ in_array($order->status, ['pending', 'diproses']) ? 3 : 3 }}" class="text-right fw-bold" style="font-size: 1.1rem;">
                                    TOTAL
                                </td>
                                <td class="text-gold fw-bold" style="font-size: 1.2rem;" colspan="2">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($order->catatan)
        <div class="card mb-3">
            <div class="card-body">
                <strong>📝 Catatan:</strong> {{ $order->catatan }}
            </div>
        </div>
        @endif

        {{-- Status Actions --}}
        @if(in_array($order->status, ['pending', 'diproses']))
        <div class="card">
            <div class="card-header">
                <h3>⚙️ Ubah Status Order</h3>
            </div>
            <div class="card-body">
                <div class="btn-group">
                    @if($order->status === 'pending')
                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="diproses">
                        <button type="submit" class="btn btn-info">▶️ Proses Order</button>
                    </form>
                    @endif

                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin batalkan order ini? Stok akan dikembalikan.')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" class="btn btn-danger">❌ Batalkan Order</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT: Tambah Item --}}
    <div class="order-cart">
        @if(in_array($order->status, ['pending', 'diproses']))
        <div class="card">
            <div class="card-header">
                <h3>➕ Tambah Item</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.addItem', $order) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Pilih Menu</label>
                        <select name="menu_id" class="form-control" required>
                            <option value="">— Pilih —</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">
                                    {{ $menu->nama_menu }} — Rp {{ number_format($menu->harga, 0, ',', '.') }} (stok: {{ $menu->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">➕ Tambahkan</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Payment Status --}}
        <div class="card mt-2">
            <div class="card-header">
                <h3>💰 Pembayaran</h3>
            </div>
            <div class="card-body text-center">
                @if($order->pembayaran)
                    <span class="badge badge-success" style="font-size: 0.9rem; padding: 8px 20px;">✅ LUNAS</span>
                    <p class="text-muted mt-2" style="font-size: 0.85rem;">
                        {{ ucfirst(str_replace('_', ' ', $order->pembayaran->metode_pembayaran)) }} •
                        {{ $order->pembayaran->dibayar_pada?->format('d/m/Y H:i') }}
                    </p>
                @else
                    @if(in_array($order->status, ['pending', 'diproses']))
                        <a href="{{ route('pembayarans.checkout', $order) }}" class="btn btn-success btn-block btn-lg">
                            💳 Proses Pembayaran
                        </a>
                    @else
                        <span class="badge badge-warning" style="font-size: 0.9rem; padding: 8px 20px;">Belum dibayar</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
