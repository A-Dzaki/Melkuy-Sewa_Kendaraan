<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.user');
title('Riwayat Peminjaman - Melakuy');

?>
<div class="min-h-screen bg-slate-50 py-12 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Info -->
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-6 shadow-sm border border-indigo-200">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 sm:text-4xl tracking-tight">Cek Riwayat Peminjaman</h1>
            <p class="mt-4 text-base text-slate-500 leading-relaxed max-w-2xl mx-auto">
                Masukkan nomor telepon yang Anda gunakan saat melakukan pemesanan untuk melihat status dan riwayat
                transaksi Anda.
            </p>
        </div>

        <!-- Search Form Card -->
        <div
            class="bg-white p-6 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 max-w-2xl mx-auto">
            <form action="{{ route('user.history') }}" method="GET" class="flex flex-col gap-4">
                <label for="phone" class="text-sm font-semibold text-slate-700">Nomor Telepon (WhatsApp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input type="tel" id="phone" name="phone" placeholder="Contoh: 081234567890"
                        value="{{ request('phone') }}" required
                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                        class="h-14 w-full pl-12 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400">
                </div>

                <div class="mt-2">
                    <x-button type="submit" variant="primary" size="lg"
                        class="w-full flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari Riwayat
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Result Area (Displayed if searched) -->
        @if (request('phone'))
            <div class="mt-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-8 bg-indigo-500 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-slate-900">Hasil Pencarian</h2>
                </div>

                @if (isset($histories) && count($histories) > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-slate-50 border-b border-slate-200 text-sm uppercase tracking-wider text-slate-500 font-semibold">
                                        <th class="px-6 py-4">Kode Peminjaman</th>
                                        <th class="px-6 py-4">Kendaraan</th>
                                        <th class="px-6 py-4">Tanggal Sewa</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($histories as $history)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <span
                                                    class="font-mono font-medium text-slate-900">{{ $history->kode ?? '#TRX-12345' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-bold text-slate-900">{{ $history->kendaraan->name ?? 'Honda Beat' }}</span>
                                                    <span class="text-sm text-slate-500">Mobil / Matic</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-slate-600">12 Okt 2026 - 15 Okt 2026</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-badge type="success">Selesai</x-badge>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="#"
                                                    class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                    Detail
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Empty Result -->
                    <div
                        class="bg-white p-10 rounded-3xl border border-slate-200 border-dashed text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Riwayat Tidak Ditemukan</h3>
                        <p class="text-slate-500 max-w-sm">
                            Tidak ada riwayat peminjaman untuk nomor telepon <strong
                                class="text-slate-700">{{ request('phone') }}</strong>.
                        </p>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
