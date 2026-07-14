<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ReservasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Meja
    Route::resource('mejas', MejaController::class)->except(['show']);

    // Manajemen Menu
    Route::resource('menus', MenuController::class)->except(['show']);

    // Order / POS
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('orders.addItem');
    Route::delete('/orders/{order}/remove-item/{item}', [OrderController::class, 'removeItem'])->name('orders.removeItem');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Reservasi
    Route::resource('reservasis', ReservasiController::class)->except(['show']);

    // Pembayaran
    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('pembayarans.index');
    Route::get('/pembayarans/{order}/checkout', [PembayaranController::class, 'checkout'])->name('pembayarans.checkout');
    Route::post('/pembayarans/{order}/pay', [PembayaranController::class, 'store'])->name('pembayarans.store');
    Route::get('/pembayarans/{order}/struk', [PembayaranController::class, 'struk'])->name('pembayarans.struk');
});
