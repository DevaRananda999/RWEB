<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🍽️</div>
        <div>
            <h2>Fine Dining</h2>
            <small>Point of Sale System</small>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('orders.create') }}" class="nav-link {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                <span class="nav-icon">🛒</span> POS — Order Baru
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Manajemen</div>
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') && !request()->routeIs('orders.create') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Daftar Order
            </a>
            <a href="{{ route('mejas.index') }}" class="nav-link {{ request()->routeIs('mejas.*') ? 'active' : '' }}">
                <span class="nav-icon">🪑</span> Manajemen Meja
            </a>
            <a href="{{ route('menus.index') }}" class="nav-link {{ request()->routeIs('menus.*') ? 'active' : '' }}">
                <span class="nav-icon">📖</span> Manajemen Menu
            </a>
            <a href="{{ route('reservasis.index') }}" class="nav-link {{ request()->routeIs('reservasis.*') ? 'active' : '' }}">
                <span class="nav-icon">📅</span> Reservasi
            </a>
            <a href="{{ route('pembayarans.index') }}" class="nav-link {{ request()->routeIs('pembayarans.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span> Pembayaran
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->role }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm btn-block">🚪 Logout</button>
        </form>
    </div>
</nav>
