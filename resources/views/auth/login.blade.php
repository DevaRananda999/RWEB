@extends('layouts.guest')

@section('content')
<div class="login-card">
    <div class="logo-section">
        <div class="logo-icon">🍽️</div>
        <h1>Fine Dining POS</h1>
        <p>Masuk ke sistem Point of Sale</p>
    </div>

    @if($errors->any())
        <div class="flash-message flash-error" style="margin-bottom: 24px;">
            <span>⚠️</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Masukkan kata sandi" required>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="remember" value="1">
                <span style="font-size: 0.85rem; color: var(--color-text-secondary);">Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
            🔐 Masuk
        </button>
    </form>

    <div style="text-align: center; margin-top: 24px; font-size: 0.75rem; color: var(--color-text-muted);">
        <p>Demo: <strong>admin</strong> / <strong>admin123</strong></p>
    </div>
</div>
@endsection
