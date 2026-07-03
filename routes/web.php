<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('layouts.User'))->name('home');

Route::get('/daftar-kendaraan', fn() => view('user.daftarkendaraan'))->name('user.daftar');

Route::get('/pencarian-kendaraan', fn() => view('user.pencariankendaraan'))->name('user.pencarian');

Route::get('/history', fn() => view('user.historyusers'))->name('user.history');
