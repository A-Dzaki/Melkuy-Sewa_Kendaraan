<?php

use App\Models\Kendaraan;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\mount;

layout('layouts.user');
title(fn () => $this->kendaraan ? $this->kendaraan->nama . ' - Melakuy' : 'Detail - Melakuy');

state(['kendaraan']);

mount(function (Kendaraan $kendaraan) {
    $this->kendaraan = $kendaraan;
});

?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm mb-8">
            <a href="{{ route('user.daftar') }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">Katalog Kendaraan</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">{{ $kendaraan->nama }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left: Gallery --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    @if($kendaraan->images->isNotEmpty())
                        <div x-data="{ mainImage: '{{ $kendaraan->thumbnail_url }}', activeId: {{ $kendaraan->thumbnail ? $kendaraan->thumbnail->id : ($kendaraan->images->first()->id ?? 'null') }} }">
                            {{-- Main Image --}}
                            <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                                <img :src="mainImage" alt="{{ $kendaraan->nama }}" class="w-full h-full object-cover transition-opacity duration-300">
                            </div>

                            {{-- Thumbnail Grid --}}
                            @if($kendaraan->images->count() > 1)
                                <div class="p-3 grid grid-cols-4 gap-2">
                                    @foreach($kendaraan->images as $image)
                                        <button type="button" 
                                            @click="mainImage = '{{ $image->image_url }}'; activeId = {{ $image->id }}"
                                            :class="activeId === {{ $image->id }} ? 'ring-2 ring-indigo-500 opacity-100' : 'opacity-60 hover:opacity-100'"
                                            class="aspect-square rounded-lg overflow-hidden bg-slate-100 transition-all focus:outline-none">
                                            <img src="{{ $image->image_url }}" alt="Gambar" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="aspect-[4/3] bg-slate-50 flex flex-col items-center justify-center text-slate-300">
                            <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Belum ada gambar</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: Details & Booking Action --}}
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                {{-- Main Info --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                                    {{ $kendaraan->jenis }}
                                </span>
                                @if($kendaraan->status === 'tersedia')
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest bg-rose-50 text-rose-600 rounded-full border border-rose-100">
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $kendaraan->nama }}</h1>
                            <p class="text-slate-500 mt-2 font-medium">{{ $kendaraan->merk }} • {{ $kendaraan->tahun }}</p>
                        </div>
                        <div class="shrink-0 text-left sm:text-right">
                            <p class="text-sm text-slate-500 font-medium mb-1">Mulai dari</p>
                            <p class="text-3xl font-black text-indigo-600">Rp {{ number_format($kendaraan->harga_sewa, 0, ',', '.') }}<span class="text-lg text-slate-500 font-medium">/hari</span></p>
                        </div>
                    </div>

                    <hr class="my-8 border-slate-200">

                    {{-- Specs Grid --}}
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Spesifikasi Kendaraan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                            <svg class="w-6 h-6 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Transmisi</span>
                            <span class="font-bold text-slate-900 mt-1">{{ $kendaraan->transmisi }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                            <svg class="w-6 h-6 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Tahun</span>
                            <span class="font-bold text-slate-900 mt-1">{{ $kendaraan->tahun }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                            <svg class="w-6 h-6 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Warna</span>
                            <span class="font-bold text-slate-900 mt-1">{{ $kendaraan->warna }}</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                            <svg class="w-6 h-6 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Merk</span>
                            <span class="font-bold text-slate-900 mt-1">{{ $kendaraan->merk }}</span>
                        </div>
                    </div>

                    @if($kendaraan->deskripsi)
                        <hr class="my-8 border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Deskripsi Lengkap</h3>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e($kendaraan->deskripsi)) !!}
                        </div>
                    @endif

                    <div class="mt-10 p-6 bg-indigo-50 border border-indigo-100 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Tertarik menyewa kendaraan ini?</h4>
                            <p class="text-sm text-slate-600 mt-1">Proses cepat tanpa ribet, langsung jalan!</p>
                        </div>
                        
                        @if($kendaraan->status === 'tersedia')
                            <a href="{{ route('user.pesan', $kendaraan->id) }}" wire:navigate
                                class="w-full sm:w-auto flex-shrink-0 inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all hover:-translate-y-1">
                                Pesan Sekarang
                            </a>
                        @else
                            <button disabled class="w-full sm:w-auto flex-shrink-0 inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold text-slate-400 bg-slate-200 cursor-not-allowed">
                                Sedang Disewa
                            </button>
                        @endif
                    </div>
                </div>
                
                {{-- Syarat & Ketentuan --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Syarat & Ketentuan Sewa
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Wajib meninggalkan KTP Asli / Dokumen pendukung saat pengambilan kendaraan.
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Pembayaran full di muka via transfer bank yang akan diinformasikan setelah pemesanan.
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Keterlambatan pengembalian akan dikenakan denda sesuai kebijakan rental.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
