<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.user');
title('Katalog Kendaraan - Melakuy');

?>
<div class="bg-slate-50 min-h-screen pb-20">
    <!-- Page Header -->
    <div class="bg-white border-b border-slate-200 pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <span
                        class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-4 border border-indigo-100">
                        Katalog Kendaraan
                    </span>
                    <h1 class="text-3xl font-black text-slate-900 sm:text-4xl tracking-tight">Pilih kendaraan yang paling
                        cocok untuk perjalananmu</h1>
                    <p class="mt-4 text-base text-slate-500 leading-relaxed">
                        Tersedia beragam motor & mobil terawat dengan harga fleksibel dan proses peminjaman yang cepat.
                    </p>
                </div>

                <!-- Filter Dropdown (Mockup) -->
                <div class="shrink-0 flex items-center gap-3">
                    <div class="relative group">
                        <button
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                    </div>

                    <div class="relative group">
                        <button
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            Urutkan
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kendaraan Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">

        @if (isset($kendaraans) && count($kendaraans) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($kendaraans as $kendaraan)
                    <x-card :title="$kendaraan->name" :description="$kendaraan->description" :image="asset('storage/' . $kendaraan->image_path)" :price="'Rp' . number_format($kendaraan->price, 0, ',', '.')" status="Tersedia"
                        {{-- Asumsi ada status 'Tersedia' dari DB, sementara dihardcode --}} {{-- link="{{ route('user.detail', $kendaraan->id) }}" --}} link="#" />
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div
                class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-slate-200 border-dashed">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada kendaraan</h3>
                <p class="text-slate-500 text-center max-w-sm mb-6">Maaf, saat ini belum ada kendaraan yang tersedia
                    atau sesuai dengan kriteria pencarian Anda.</p>
                <x-button href="{{ route('home') }}" variant="primary">
                    Kembali ke Beranda
                </x-button>
            </div>
        @endif

        <!-- Pagination Mockup (jika perlu) -->
        @if (isset($kendaraans) && count($kendaraans) > 0)
            <div class="mt-12 flex justify-center">
                <nav class="inline-flex -space-x-px rounded-xl shadow-sm" aria-label="Pagination">
                    <a href="#"
                        class="relative inline-flex items-center rounded-l-xl border border-slate-300 bg-white px-2 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" aria-current="page"
                        class="relative z-10 inline-flex items-center border border-indigo-500 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600 focus:z-20">1</a>
                    <a href="#"
                        class="relative inline-flex items-center border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 focus:z-20">2</a>
                    <a href="#"
                        class="relative inline-flex items-center border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 focus:z-20">3</a>
                    <a href="#"
                        class="relative inline-flex items-center rounded-r-xl border border-slate-300 bg-white px-2 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        @endif
    </div>
</div>
