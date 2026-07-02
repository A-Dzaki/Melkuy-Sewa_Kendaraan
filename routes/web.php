<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('User.dasboarduser');
})->name('home');

Route::get('/daftar-kendaraan', function () {
    return view('User.daftarkendaraan');
})->name('user.daftar');

Route::get('/pencarian-kendaraan', function () {
    return view('User.Pencariankendaraan');
})->name('user.pencarian');

Route::get('/history', function () {
    return view('User.historyusers');
})->name('user.history');
