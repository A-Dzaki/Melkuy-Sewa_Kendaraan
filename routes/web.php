<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\OAuthController;



Volt::route('/', 'user.home')->name('home');

Volt::route('/daftar-kendaraan', 'user.daftar-kendaraan')
    ->name('user.daftar');

Volt::route('/pencarian-kendaraan', 'user.pencarian-kendaraan')
    ->name('user.pencarian');

Volt::route('/history', 'user.form_peminjaman.history-users')
    ->name('user.history');
// Auth Routes (Placeholder to prevent route not defined errors)
Route::get('/login', fn() => view('auth.login'))->name('login');
// Route untuk melempar/mengalihkan ke Google
Route::get('admin/login/google', [OAuthController::class, 'redirectToGoogle'])->name('login.google');

// Route callback yang dipanggil Google setelah user login sukses
Route::get('admin/login/google/callback', [OAuthController::class, 'handleGoogleCallback']);

// Admin Routes (Placeholder)
Volt::route('/dashboard', 'admin.dashboard-admin')
    ->name('admin.dashboard');
Volt::route('/pemesanan', 'admin.form-pesanan.pemesanan')->name('admin.pemesanan');
