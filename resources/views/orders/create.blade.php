@extends('layouts.app')

@section('title', 'POS — Order Baru')
@section('page-title', 'POS — Buat Order Baru')

@section('content')
<form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf

    <div class="pos-layout">
        {{-- LEFT: Pilih Meja & Menu --}}
        <div>
            {{-- Pilih Meja --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3>🪑 Pilih Meja</h3>
                </div>
                <div class="card-body">
                    <select name="meja_id" class="form-control" required id="mejaSelect">
                        <option value="">— Pilih Meja —</option>
                        @foreach($mejas as $meja)
                            <option value="{{ $meja->id }}"
                                {{ (old('meja_id') == $meja->id || ($mejaTerpilih && $mejaTerpilih->id == $meja->id)) ? 'selected' : '' }}
                                {{ $meja->status === 'occupied' ? 'disabled' : '' }}>
                                Meja {{ $meja->nomor_meja }} ({{ $meja->kapasitas }} orang)
                                {{ $meja->status !== 'available' ? '— '.$meja->status : '' }}
                            </option>
                        @endforeach
                    </select>

                    <div class="form-group mt-2">
                        <label class="form-label" for="catatan">Catatan Order</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="2"
                                  placeholder="Catatan khusus (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Daftar Menu --}}
            <div class="card">
                <div class="card-header">
                    <h3>📖 Pilih Menu</h3>
                    <input type="text" id="searchMenu" class="form-control" placeholder="🔍 Cari menu..."
                           style="max-width: 220px;">
                </div>
                <div class="card-body">
                    {{-- Kategori tabs --}}
                    <div class="d-flex gap-1 mb-3" style="flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm btn-outline-gold kategori-filter active" data-kategori="all">Semua</button>
                        <button type="button" class="btn btn-sm btn-secondary kategori-filter" data-kategori="Appetizer">Appetizer</button>
                        <button type="button" class="btn btn-sm btn-secondary kategori-filter" data-kategori="Main Course">Main Course</button>
                        <button type="button" class="btn btn-sm btn-secondary kategori-filter" data-kategori="Dessert">Dessert</button>
                        <button type="button" class="btn btn-sm btn-secondary kategori-filter" data-kategori="Drink">Drink</button>
                    </div>

                    <div class="pos-menu-grid" id="menuGrid">
                        @foreach($menus as $menu)
                        <div class="pos-menu-item" data-id="{{ $menu->id }}" data-nama="{{ $menu->nama_menu }}"
                             data-harga="{{ $menu->harga }}" data-stok="{{ $menu->stok }}"
                             data-kategori="{{ $menu->kategori }}"
                             onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, {{ $menu->stok }})">
                            <div class="menu-kategori">{{ $menu->kategori }}</div>
                            <div class="menu-name">{{ $menu->nama_menu }}</div>
                            <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                            <div class="menu-stock">Stok: {{ $menu->stok }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="order-cart">
            <div class="card">
                <div class="card-header">
                    <h3>🛒 Pesanan</h3>
                    <span class="badge badge-gold" id="itemCount">0 item</span>
                </div>
                <div class="card-body">
                    <div id="cartItems">
                        <div class="empty-state" id="emptyCart">
                            <div class="empty-icon">🛒</div>
                            <p>Klik menu untuk menambahkan</p>
                        </div>
                    </div>

                    <div class="cart-total" id="cartTotal" style="display: none;">
                        <span class="total-label">Total</span>
                        <span class="total-value" id="totalValue">Rp 0</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitOrder" disabled>
                        ✅ Buat Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden inputs for items --}}
    <div id="hiddenItems"></div>
</form>

@push('scripts')
<script>
    let cart = {};
    let itemIndex = 0;

    function addToCart(id, nama, harga, stok) {
        if (cart[id]) {
            if (cart[id].jumlah >= stok) {
                alert('Stok ' + nama + ' tidak mencukupi!');
                return;
            }
            cart[id].jumlah++;
        } else {
            cart[id] = { id, nama, harga, stok, jumlah: 1 };
        }
        renderCart();
    }

    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    function updateQty(id, delta) {
        if (!cart[id]) return;
        let newQty = cart[id].jumlah + delta;
        if (newQty <= 0) {
            removeFromCart(id);
            return;
        }
        if (newQty > cart[id].stok) {
            alert('Stok tidak mencukupi!');
            return;
        }
        cart[id].jumlah = newQty;
        renderCart();
    }

    function renderCart() {
        const cartDiv = document.getElementById('cartItems');
        const hiddenDiv = document.getElementById('hiddenItems');
        const totalDiv = document.getElementById('cartTotal');
        const emptyDiv = document.getElementById('emptyCart');
        const totalVal = document.getElementById('totalValue');
        const countBadge = document.getElementById('itemCount');
        const submitBtn = document.getElementById('submitOrder');

        const keys = Object.keys(cart);

        if (keys.length === 0) {
            cartDiv.innerHTML = '<div class="empty-state" id="emptyCart"><div class="empty-icon">🛒</div><p>Klik menu untuk menambahkan</p></div>';
            totalDiv.style.display = 'none';
            hiddenDiv.innerHTML = '';
            countBadge.textContent = '0 item';
            submitBtn.disabled = true;
            return;
        }

        let html = '';
        let hiddenHtml = '';
        let total = 0;
        let totalItems = 0;
        let idx = 0;

        keys.forEach(id => {
            const item = cart[id];
            const subtotal = item.harga * item.jumlah;
            total += subtotal;
            totalItems += item.jumlah;

            html += `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.nama}</div>
                        <div class="item-price">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="updateQty(${id}, -1)" style="padding:3px 8px;font-size:0.75rem;">−</button>
                            <span style="margin: 0 8px; font-weight: 600;">${item.jumlah}</span>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="updateQty(${id}, 1)" style="padding:3px 8px;font-size:0.75rem;">+</button>
                            × Rp ${numberFormat(item.harga)}
                        </div>
                    </div>
                    <div>
                        <div class="item-subtotal">Rp ${numberFormat(subtotal)}</div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${id})" style="padding:3px 8px;font-size:0.7rem;margin-top:4px;">✕</button>
                    </div>
                </div>
            `;

            hiddenHtml += `<input type="hidden" name="items[${idx}][menu_id]" value="${id}">`;
            hiddenHtml += `<input type="hidden" name="items[${idx}][jumlah]" value="${item.jumlah}">`;
            idx++;
        });

        cartDiv.innerHTML = html;
        hiddenDiv.innerHTML = hiddenHtml;
        totalDiv.style.display = 'flex';
        totalVal.textContent = 'Rp ' + numberFormat(total);
        countBadge.textContent = totalItems + ' item';
        submitBtn.disabled = false;
    }

    function numberFormat(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }

    // Search menu
    document.getElementById('searchMenu').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.pos-menu-item').forEach(el => {
            const nama = el.dataset.nama.toLowerCase();
            el.style.display = nama.includes(q) ? '' : 'none';
        });
    });

    // Kategori filter
    document.querySelectorAll('.kategori-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.kategori-filter').forEach(b => {
                b.classList.remove('active');
                b.classList.remove('btn-outline-gold');
                b.classList.add('btn-secondary');
            });
            this.classList.add('active');
            this.classList.remove('btn-secondary');
            this.classList.add('btn-outline-gold');

            const kat = this.dataset.kategori;
            document.querySelectorAll('.pos-menu-item').forEach(el => {
                if (kat === 'all' || el.dataset.kategori === kat) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
