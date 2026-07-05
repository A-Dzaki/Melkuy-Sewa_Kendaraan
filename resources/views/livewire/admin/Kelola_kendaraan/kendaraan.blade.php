<?php

use App\Models\Kendaraan;
use function Livewire\Volt\{state, computed, layout, title, on, usesPagination};

layout('layouts.admin');
title('Kelola Kendaraan');

usesPagination();

state([
    'search' => '',
    'filterJenis' => '',
    'filterStatus' => '',
    'filterTransmisi' => '',
    'sortBy' => 'created_at',
    'sortDir' => 'desc',
    'perPage' => 10,
    'deleteId' => null,
]);

$kendaraans = computed(function () {
    return Kendaraan::query()
        ->with('images')
        ->when($this->search, fn($q, $s) => $q->search($s))
        ->when($this->filterJenis, fn($q, $j) => $q->byJenis($j))
        ->when($this->filterStatus, fn($q, $s) => $q->where('status', $s))
        ->when($this->filterTransmisi, fn($q, $t) => $q->byTransmisi($t))
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
});

$sort = function (string $column) {
    if ($this->sortBy === $column) {
        $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortBy = $column;
        $this->sortDir = 'asc';
    }
};

$resetFilters = function () {
    $this->search = '';
    $this->filterJenis = '';
    $this->filterStatus = '';
    $this->filterTransmisi = '';
    $this->sortBy = 'created_at';
    $this->sortDir = 'desc';
};

$confirmDelete = function (int $id) {
    $this->deleteId = $id;
};

$cancelDelete = function () {
    $this->deleteId = null;
};

$delete = function () {
    if ($this->deleteId) {
        $kendaraan = Kendaraan::findOrFail($this->deleteId);
        $nama = $kendaraan->nama;

        // Hapus gambar dari storage
        foreach ($kendaraan->images as $image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image);
        }

        $kendaraan->delete();
        $this->deleteId = null;

        session()->flash('success', "Kendaraan \"{$nama}\" berhasil dihapus.");
    }
};

// Reset pagination saat search/filter berubah
$updatedSearch = function () { $this->resetPage(); };
$updatedFilterJenis = function () { $this->resetPage(); };
$updatedFilterStatus = function () { $this->resetPage(); };
$updatedFilterTransmisi = function () { $this->resetPage(); };

?>

<div>
    {{-- Flash Message --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transform ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-2 opacity-0"
            class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="flex flex-col gap-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Kendaraan</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola seluruh data kendaraan yang tersedia untuk disewakan.</p>
            </div>
            <x-button variant="primary" href="{{ route('admin.kendaraan.create') }}" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kendaraan
            </x-button>
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $totalKendaraan = \App\Models\Kendaraan::count();
                $tersedia = \App\Models\Kendaraan::where('status', 'tersedia')->count();
                $dipinjam = \App\Models\Kendaraan::where('status', 'dipinjam')->count();
                $maintenance = \App\Models\Kendaraan::where('status', 'maintenance')->count();
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Total</p>
                    <p class="text-lg font-bold text-slate-900">{{ $totalKendaraan }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Tersedia</p>
                    <p class="text-lg font-bold text-emerald-600">{{ $tersedia }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Dipinjam</p>
                    <p class="text-lg font-bold text-amber-600">{{ $dipinjam }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Maintenance</p>
                    <p class="text-lg font-bold text-rose-600">{{ $maintenance }}</p>
                </div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">

            {{-- Toolbar: Search + Filters --}}
            <div class="p-6 border-b border-slate-200 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    {{-- Search --}}
                    <div class="relative max-w-md w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" wire:model.live.debounce.300ms="search"
                            placeholder="Cari nama, merk, atau kode kendaraan..."
                            class="h-10 w-full pl-10 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400 transition-all">
                    </div>

                    {{-- Filter Dropdowns --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <select wire:model.live="filterJenis"
                            class="h-10 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                        </select>

                        <select wire:model.live="filterStatus"
                            class="h-10 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipesan">Dipesan</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="maintenance">Maintenance</option>
                        </select>

                        <select wire:model.live="filterTransmisi"
                            class="h-10 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            <option value="">Semua Transmisi</option>
                            <option value="Manual">Manual</option>
                            <option value="Matic">Matic</option>
                        </select>

                        @if($search || $filterJenis || $filterStatus || $filterTransmisi)
                            <button wire:click="resetFilters"
                                class="h-10 px-3 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors">
                                Reset
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Loading Overlay --}}
            <div wire:loading.delay class="p-4 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-slate-500">
                    <svg class="animate-spin h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat data...
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto" wire:loading.remove>
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-xs uppercase tracking-widest text-slate-500 font-bold border-b border-slate-200">
                            <th class="px-6 py-4">Kendaraan</th>
                            <th class="px-6 py-4 cursor-pointer hover:text-indigo-600 transition-colors" wire:click="sort('jenis')">
                                <span class="inline-flex items-center gap-1">
                                    Jenis
                                    @if($sortBy === 'jenis')
                                        <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @endif
                                </span>
                            </th>
                            <th class="px-6 py-4">Transmisi</th>
                            <th class="px-6 py-4 cursor-pointer hover:text-indigo-600 transition-colors" wire:click="sort('harga_sewa')">
                                <span class="inline-flex items-center gap-1">
                                    Harga/Hari
                                    @if($sortBy === 'harga_sewa')
                                        <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @endif
                                </span>
                            </th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($this->kendaraans as $kendaraan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                {{-- Kendaraan Info --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                            @if($kendaraan->thumbnail)
                                                <img src="{{ $kendaraan->thumbnail_url }}" alt="{{ $kendaraan->nama }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $kendaraan->nama }}</span>
                                            <span class="text-xs text-slate-500">{{ $kendaraan->merk }} · {{ $kendaraan->warna }} · {{ $kendaraan->tahun }}</span>
                                            <span class="text-xs text-slate-400 font-mono mt-0.5">{{ $kendaraan->kode_kendaraan }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Jenis --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-sm text-slate-700">
                                        @if($kendaraan->jenis === 'mobil')
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17h.01M16 17h.01M3 11l1.5-5A2 2 0 016.4 4.5h11.2a2 2 0 011.9 1.5L21 11M3 11v6a1 1 0 001 1h1m16-7v6a1 1 0 01-1 1h-1M3 11h18" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        @endif
                                        {{ ucfirst($kendaraan->jenis) }}
                                    </span>
                                </td>

                                {{-- Transmisi --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-700">{{ $kendaraan->transmisi }}</span>
                                </td>

                                {{-- Harga --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-slate-900">{{ $kendaraan->formatted_harga }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @php
                                        $statusBadge = match($kendaraan->status) {
                                            'tersedia' => 'success',
                                            'dipesan' => 'info',
                                            'dipinjam' => 'warning',
                                            'maintenance' => 'danger',
                                            default => 'default',
                                        };
                                    @endphp
                                    <x-badge :type="$statusBadge">
                                        {{ ucfirst($kendaraan->status) }}
                                    </x-badge>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.kendaraan.detail', $kendaraan) }}" wire:navigate
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.kendaraan.edit', $kendaraan) }}" wire:navigate
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.kendaraan.images', $kendaraan) }}" wire:navigate
                                            class="p-2 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors"
                                            title="Kelola Gambar">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                        <button wire:click="confirmDelete({{ $kendaraan->id }})"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="6" class="px-6 py-16">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300 mb-4">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-semibold text-slate-900 mb-1">Belum ada kendaraan</h3>
                                        <p class="text-sm text-slate-500 mb-4">
                                            @if($search || $filterJenis || $filterStatus || $filterTransmisi)
                                                Tidak ada kendaraan yang sesuai dengan filter. Coba ubah kriteria pencarian.
                                            @else
                                                Mulai tambahkan kendaraan pertama Anda untuk memulai bisnis rental.
                                            @endif
                                        </p>
                                        @if(!$search && !$filterJenis && !$filterStatus && !$filterTransmisi)
                                            <x-button variant="primary" size="sm" href="{{ route('admin.kendaraan.create') }}" wire:navigate>
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah Kendaraan
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($this->kendaraans->hasPages())
                <div class="p-6 border-t border-slate-200">
                    {{ $this->kendaraans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($deleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="document.body.style.overflow = 'hidden'" x-destroy="document.body.style.overflow = ''">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="cancelDelete"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus Kendaraan?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        Tindakan ini tidak dapat dibatalkan. Semua data termasuk gambar kendaraan akan dihapus secara permanen.
                    </p>
                    <div class="flex items-center gap-3 w-full">
                        <x-button variant="outline" wire:click="cancelDelete" class="flex-1">
                            Batal
                        </x-button>
                        <x-button variant="danger" wire:click="delete" class="flex-1">
                            <svg class="w-4 h-4 mr-1" wire:loading.remove wire:target="delete" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
