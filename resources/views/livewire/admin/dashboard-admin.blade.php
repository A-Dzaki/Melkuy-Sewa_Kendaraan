<?php

use function Livewire\Volt\layout;
use App\Models\Peminjaman;
use function Livewire\Volt\state;
use function Livewire\Volt\with;
use function Livewire\Volt\usesPagination;
layout('layouts.admin');
with(function () {
    $query = Peminjaman::with(['kendaraan', 'pembayaran'])->latest();

    return [
        'pemesanans' => $query->paginate(10),
    ];
});
?>


<div>
    <div class="flex flex-col gap-8">

        <!-- Welcome Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-indigo-600 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-500/20">
            <!-- Decoration -->
            <div class="absolute -right-10 -top-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute right-20 -bottom-20 w-48 h-48 bg-indigo-400/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <h2 class="text-2xl font-bold mb-1">Selamat datang kembali</h2>
                <p class="text-indigo-100 text-sm md:text-base max-w-xl">Berikut adalah ringkasan performa penyewaan
                    kendaraan Melakuy bulan ini.</p>
            </div>
            <div class="relative z-10 shrink-0">
                <x-button variant="outline"
                    class="!bg-white/10 !text-white !border-white/20 hover:!bg-white/20 backdrop-blur-sm">
                    Unduh Laporan
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </x-button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Kendaraan -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Kendaraan</p>
                        <h3 class="text-2xl font-bold text-slate-900">124</h3>
                    </div>
                </div>

            </div>

            <!-- Kendaraan Disewa -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Sedang Disewa</p>
                        <h3 class="text-2xl font-bold text-slate-900">38</h3>
                    </div>
                </div>

            </div>

            <!-- Pendapatan bulanan -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pendapatan Bulan Ini</p>
                        <h3 class="text-2xl font-bold text-slate-900">Rp24.5M</h3>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabel Pemesanan Terbaru -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Pemesanan Terbaru</h3>
                <a href="{{ route('admin.pemesanan') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat
                    Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-sm uppercase tracking-wider text-slate-500 font-semibold">
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
                        @if ($pemesanans->isNotEmpty())
                            @foreach ($pemesanans as $transaction)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-sm font-medium text-slate-900">
                                        {{ $transaction->kode_booking }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs shrink-0">
                                                {{ $transaction->initials }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-bold text-slate-900">{{ $transaction->nama }}</span>
                                                <span class="text-xs text-slate-500">{{ $transaction->no_hp }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-slate-900">{{ $transaction->kendaraan->nama ?? '-' }}</span>
                                            <span
                                                class="text-xs text-slate-500">{{ ucfirst($transaction->kendaraan->jenis ?? '-') }}
                                                / {{ ucfirst($transaction->kendaraan->transmisi ?? '-') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600">{{ $transaction->jadwal_sewa }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-medium text-slate-900">{{ $transaction->formatted_total_harga }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($transaction->pembayaran)
                                            <span
                                                class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-md">{{ ucfirst($transaction->pembayaran->metode) }}</span>
                                        @else
                                            <span
                                                class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">Belum
                                                Dibayar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge type="{{ $transaction->status_badge_type }}">
                                            {{ $transaction->status_label }}
                                        </x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if (in_array($transaction->status, ['approved', 'paid', 'picked_up']))
                                            <svg class="w-6 h-6 mx-auto text-slate-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.pemesanan.detail', $transaction->id) }}"
                                            class="text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-slate-500">Tidak ada
                                    transaksi baru.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
