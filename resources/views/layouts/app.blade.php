<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem POS Restoran Fine Dining — Kelola pesanan, meja, menu, dan pembayaran dengan elegan.">
    <title>@yield('title', 'Dashboard') — Fine Dining POS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="main-content">
            <div class="top-bar">
                <div>
                    <button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="breadcrumb">
                    <span>@yield('page-title', 'Dashboard')</span>
                </div>
            </div>

            <div class="content-area">
                @include('components.flash-message')
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
