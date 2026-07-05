<?php

use App\Models\Kendaraan;
use function Livewire\Volt\{state, layout, title, mount, computed};

layout('layouts.admin');
title('Detail Kendaraan');

state([
    'kendaraan' => null,
    'deleteId' => null,
]);

mount(function (Kendaraan $kendaraan) {
    $this->kendaraan = $kendaraan->load(['images', 'peminjamans' => function ($q) {
        $q->latest()->limit(5);
    }]);
});

$confirmDelete = function () {
    $this->deleteId = $this->kendaraan->id;
};

$cancelDelete = function () {
    $this->deleteId = null;
};

$delete = function () {
    $nama = $this->kendaraan->nama;

    foreach ($this->kendaraan->images as $image) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image);
    }

    $this->kendaraan->delete();
    session()->flash('success', "Kendaraan \"{$nama}\" berhasil dihapus.");
    $this->redirect(route('admin.kendaraan'), navigate: true);
};

?>

<div>
    <div class="flex flex-col gap-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.kendaraan') }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">Kendaraan</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">{{ $kendaraan->nama }}</span>
        </nav>

        {{-- Page Header + Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $kendaraan->nama }}</h2>
                <p class="text-sm text-slate-500 mt-1 font-mono">{{ $kendaraan->kode_kendaraan }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="outline" size="sm" href="{{ route('admin.kendaraan.images', $kendaraan) }}" wire:navigate>
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Kelola Gambar
                </x-button>
                <x-button variant="primary" size="sm" href="{{ route('admin.kendaraan.edit', $kendaraan) }}" wire:navigate>
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </x-button>
                <x-button variant="danger" size="sm" wire:click="confirmDelete">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Gallery --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900">Galeri</h3>
                    </div>

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
                            <a href="{{ route('admin.kendaraan.images', $kendaraan) }}" wire:navigate
                                class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Tambah Gambar →</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: Detail Info --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Info Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900">Informasi Kendaraan</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Kode</dt>
                                <dd class="mt-1 text-sm font-mono font-semibold text-slate-900">{{ $kendaraan->kode_kendaraan }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Status</dt>
                                <dd class="mt-1">
                                    @php
                                        $statusBadge = match($kendaraan->status) {
                                            'tersedia' => 'success',
                                            'dipesan' => 'info',
                                            'dipinjam' => 'warning',
                                            'maintenance' => 'danger',
                                            default => 'default',
                                        };
                                    @endphp
                                    <x-badge :type="$statusBadge">{{ ucfirst($kendaraan->status) }}</x-badge>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Jenis</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ ucfirst($kendaraan->jenis) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Merk</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kendaraan->merk }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Nama</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $kendaraan->nama }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Tahun</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kendaraan->tahun }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Warna</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kendaraan->warna }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Transmisi</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kendaraan->transmisi }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Harga Sewa / Hari</dt>
                                <dd class="mt-1 text-lg font-bold text-indigo-600">{{ $kendaraan->formatted_harga }}</dd>
                            </div>
                            @if($kendaraan->deskripsi)
                                <div class="sm:col-span-2">
                                    <dt class="text-xs text-slate-500 font-medium uppercase tracking-wider">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-slate-700 leading-relaxed">{{ $kendaraan->deskripsi }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Riwayat Peminjaman --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Peminjaman Terakhir</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50/50 text-xs uppercase tracking-widest text-slate-500 font-bold border-b border-slate-200">
                                    <th class="px-6 py-3">Kode Booking</th>
                                    <th class="px-6 py-3">Pemesan</th>
                                    <th class="px-6 py-3">Jadwal</th>
                                    <th class="px-6 py-3">Total</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($kendaraan->peminjamans as $peminjaman)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-3 text-sm font-mono font-medium text-slate-900">{{ $peminjaman->kode_booking }}</td>
                                        <td class="px-6 py-3">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-900">{{ $peminjaman->nama }}</span>
                                                <span class="text-xs text-slate-500">{{ $peminjaman->no_hp }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-slate-700">{{ $peminjaman->jadwal_sewa }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-slate-900">{{ $peminjaman->formatted_total_harga }}</td>
                                        <td class="px-6 py-3">
                                            <x-badge :type="$peminjaman->status_badge_type">{{ $peminjaman->status_label }}</x-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center">
                                            <p class="text-sm text-slate-400">Belum ada riwayat peminjaman untuk kendaraan ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($deleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="cancelDelete"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus "{{ $kendaraan->nama }}"?</h3>
                    <p class="text-sm text-slate-500 mb-6">Semua data termasuk gambar dan riwayat peminjaman akan dihapus permanen.</p>
                    <div class="flex items-center gap-3 w-full">
                        <x-button variant="outline" wire:click="cancelDelete" class="flex-1">Batal</x-button>
                        <x-button variant="danger" wire:click="delete" class="flex-1">
                            <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
