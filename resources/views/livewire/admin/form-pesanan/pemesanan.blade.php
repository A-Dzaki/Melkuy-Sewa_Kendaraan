<?php

use function Livewire\Volt\layout;

layout('layouts.admin');

?>

    <div class="flex flex-col gap-6">

        <!-- Page Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Daftar Pemesanan</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau seluruh transaksi penyewaan kendaraan.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Bungkus dengan div yang memiliki x-data -->
                <div x-data="{ open: false }" class="relative inline-block text-left">

                    <!-- Tombol Filter Utama -->
                    <x-button @click="open = !open" variant="outline" class="!bg-white flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </x-button>

                    <!-- Dropdown Pilihan Filter -->
                    <!-- x-show mengatur tampil/hilang, @click.outside menutup jika klik di luar -->
                    <div x-show="open" @click.outside="open = false" style="display: none;"
                        class="absolute left-0 mt-2 z-10 flex flex-col gap-1 bg-slate-100 p-1 rounded-xl shadow-lg border min-w-[150px]">

                        <button
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg bg-white text-slate-800 shadow-sm text-left">
                            Semua
                        </button>
                        <button
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-colors text-left">
                            Menunggu
                        </button>
                        <button
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-colors text-left">
                            Aktif
                        </button>
                        <button
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-colors text-left">
                            Selesai
                        </button>
                    </div>
                </div>
                <x-button variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pesanan Manual
                </x-button>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">

            <!-- Table Header Toolbar -->
            <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <!-- Search -->
                <div class="relative max-w-md w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="search" placeholder="Cari kode pesanan, atau no HP..."
                        class="h-10 w-full pl-10 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400 transition-all">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50/50 text-xs uppercase tracking-widest text-slate-500 font-bold border-b border-slate-200">
                            <th class="px-6 py-4">id_pesanan</th>
                            <th class="px-6 py-4">Informasi Pelanggan</th>
                            <th class="px-6 py-4">Kendaraan Disewa</th>
                            <th class="px-6 py-4">Jadwal Sewa</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4">Pembayaran</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">QR</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <!-- akan melakukan perulangan untuk setiap transaksi -->
                        {{-- @if ($pemesanan->isNotEmpty())
                            @foreach ($pemesanan as $transaction)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-sm font-medium text-slate-900">
                                        {{ $transaction->code }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs shrink-0">
                                                {{ substr($transaction->user->name, 0, 2) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-bold text-slate-900">{{ $transaction->user->name }}</span>
                                                <span class="text-xs text-slate-500">{{ $transaction->user->phone }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-slate-900">{{ $transaction->vehicle->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $transaction->vehicle->category->name }}
                                                / {{ $transaction->vehicle->transmission }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge type="{{ $transaction->status === 'pending' ? 'warning' : 'success' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else --}}
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-slate-50₀">Tidak ada transaksi
                                baru.</td>
                        </tr>
                        {{-- @endif --}}
                    </tbody>
                </table>
            </div>

            <!-- Table Footer / Pagination -->
            <div class="p-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-sm text-slate-500">Menampilkan <span class="font-medium text-slate-900">0</span> dari
                    <span class="font-medium text-slate-900">0</span> transaksi</span>
                <div class="flex items-center gap-1">
                    <button
                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-300 text-slate-400 hover:bg-slate-50 disabled:opacity-50"
                        disabled>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-medium shadow-sm shadow-indigo-600/30">1</button>

                    <span class="px-2 text-slate-400">...</span>
                    <button
                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

