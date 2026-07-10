<?php

use App\Models\Peminjaman;
use Livewire\Attributes\Url;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;
use function Livewire\Volt\with;
use function Livewire\Volt\usesPagination;

usesPagination();
layout('layouts.admin');

state(['search' => ''])->url();
state(['statusFilter' => 'semua'])->url();

with(function () {
    $query = Peminjaman::with(['kendaraan', 'pembayaran'])->latest();

    if ($this->search) {
        $query->where(function ($q) {
            $q->where('kode_booking', 'like', '%' . $this->search . '%')
              ->orWhere('no_hp', 'like', '%' . $this->search . '%')
              ->orWhere('nama', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->statusFilter && $this->statusFilter !== 'semua') {
        if ($this->statusFilter === 'menunggu') {
            $query->where('status', 'pending');
        } elseif ($this->statusFilter === 'aktif') {
            $query->whereIn('status', ['approved', 'paid', 'picked_up']);
        } elseif ($this->statusFilter === 'selesai') {
            $query->where('status', 'returned');
        }
    }

    return [
        'pemesanans' => $query->paginate(10)
    ];
});

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

                        <button wire:click="$set('statusFilter', 'semua')"
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg {{ $statusFilter === 'semua' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }} transition-colors text-left">
                            Semua
                        </button>
                        <button wire:click="$set('statusFilter', 'menunggu')"
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg {{ $statusFilter === 'menunggu' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }} transition-colors text-left">
                            Menunggu
                        </button>
                        <button wire:click="$set('statusFilter', 'aktif')"
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg {{ $statusFilter === 'aktif' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }} transition-colors text-left">
                            Aktif
                        </button>
                        <button wire:click="$set('statusFilter', 'selesai')"
                            class="px-4 py-1.5 text-sm font-semibold rounded-lg {{ $statusFilter === 'selesai' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }} transition-colors text-left">
                            Selesai
                        </button>
                    </div>
                </div>
                <x-button href="{{ route('user.daftar') }}" variant="primary">
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
                    <input type="search" placeholder="Cari kode pesanan, nama atau no HP..." wire:model.live="search"
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
                                            <span class="text-xs text-slate-500">{{ ucfirst($transaction->kendaraan->jenis ?? '-') }}
                                                / {{ ucfirst($transaction->kendaraan->transmisi ?? '-') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600">{{ $transaction->jadwal_sewa }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-slate-900">{{ $transaction->formatted_total_harga }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($transaction->pembayaran)
                                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-md">{{ ucfirst($transaction->pembayaran->metode) }}</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">Belum Dibayar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge type="{{ $transaction->status_badge_type }}">
                                            {{ $transaction->status_label }}
                                        </x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(in_array($transaction->status, ['approved', 'paid', 'picked_up']))
                                            <svg class="w-6 h-6 mx-auto text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.pemesanan.detail', $transaction->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-slate-500">Tidak ada transaksi baru.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Table Footer / Pagination -->
            <div class="p-6 border-t border-slate-200">
                {{ $pemesanans->links('vendor.livewire.tailwind') }}
            </div>
        </div>

    </div>

