<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\AuthController;

// ══════════════════════════════════════════════
// Guest Routes (Public)
// ══════════════════════════════════════════════

Volt::route('/', 'user.home')->name('home');

Volt::route('/daftar-kendaraan', 'user.daftar-kendaraan')
    ->name('user.daftar');

Volt::route('/pencarian-kendaraan', 'user.pencarian-kendaraan')
    ->name('user.pencarian');

Volt::route('/kendaraan/{kendaraan}', 'user.detail-kendaraan')
    ->name('user.detail');

Volt::route('/pesan/{kendaraan}', 'user.Form_peminjaman.create-pesanan')
    ->name('user.pesan');

Volt::route('/pembayaran/{peminjaman}', 'user.Form_peminjaman.pembayaran')
    ->name('user.pembayaran');

Volt::route('/history', 'user.Form_peminjaman.history-users')
    ->name('user.history');

Volt::route('/history/{peminjaman}', 'user.Form_peminjaman.cekdetail-history')
    ->name('user.history.detail');

// ══════════════════════════════════════════════
// Auth Routes
// ══════════════════════════════════════════════

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// Route untuk melempar/mengalihkan ke Google
Route::get('admin/login/google', [OAuthController::class, 'redirectToGoogle'])
    ->name('login.google');

// Route callback yang dipanggil Google setelah user login sukses
Route::get('admin/login/google/callback', [OAuthController::class, 'handleGoogleCallback']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')
        ->with('success', 'Anda berhasil logout.');
})->middleware('auth')->name('logout');

// ══════════════════════════════════════════════
// Admin Routes (Protected)
// ══════════════════════════════════════════════

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Volt::route('/dashboard', 'admin.dashboard-admin')
        ->name('dashboard');

    // Kelola Pemesanan
    Volt::route('/pemesanan', 'admin.form-pesanan.pemesanan')
        ->name('pemesanan');

    Volt::route('/pemesanan/{peminjaman}', 'admin.form-pesanan.detail-pemesanan')
        ->name('pemesanan.detail');

    Volt::route('/pembayaran', 'admin.form-pesanan.pembayaran')
        ->name('pembayaran');

    Volt::route('/laporan', 'admin.form-pesanan.laporan')
        ->name('laporan');

    // Kelola Kendaraan
    Volt::route('/kendaraan', 'admin.Kelola_kendaraan.kendaraan')
        ->name('kendaraan');

    Volt::route('/kendaraan/create', 'admin.Kelola_kendaraan.createkendaraan')
        ->name('kendaraan.create');

    Volt::route('/kendaraan/{kendaraan}', 'admin.Kelola_kendaraan.detailkendaraan')
        ->name('kendaraan.detail');

    Volt::route('/kendaraan/{kendaraan}/edit', 'admin.Kelola_kendaraan.editkendaraan')
        ->name('kendaraan.edit');

    Volt::route('/kendaraan/{kendaraan}/images', 'admin.Kelola_kendaraan.image-kendaraan')
        ->name('kendaraan.images');

    // Notifikasi
    Volt::route('/notifikasi', 'admin.notifikasi')
        ->name('notifikasi');
});
