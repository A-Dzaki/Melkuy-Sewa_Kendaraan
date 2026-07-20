<?php

use Livewire\WithPagination;
use function Livewire\Volt\{layout, title, state, uses, computed};

use App\Models\Kendaraan;

layout('layouts.user');
title('Katalog Kendaraan');

uses([WithPagination::class]);

state([
    'jenis_kendaraan' => '',
]);

$updatedJenisKendaraan = function () {
    $this->resetPage();
};

$kendaraans = computed(function () {
    return Kendaraan::with('images')->where('status', 'tersedia')->when($this->jenis_kendaraan, fn($q) => $q->where('jenis', $this->jenis_kendaraan))->latest()->paginate(8);
});
?>
<div class="bg-slate-50 min-h-screen pb-20">
    <!-- Page Header -->
    <div class="bg-[#eff0f3] border-b border-slate-200 pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <span
                        class="inline-flex items-center rounded-full bg-[#ff8e3c] px-3 py-1 text-xs font-semibold uppercase tracking-widest text-white mb-4 border border-[#ff8e3c]">
                        Katalog Kendaraan
                    </span>
                    <h1 class="text-3xl font-black text-slate-900 sm:text-4xl tracking-tight">Pilih kendaraan yang paling
                        cocok untuk perjalananmu</h1>
                    <p class="mt-4 text-base text-slate-500 leading-relaxed">
                        Tersedia beragam motor & mobil terawat dengan harga fleksibel dan proses peminjaman yang cepat.
                    </p>
                </div>

                <!-- Filter Dropdown (Mockup) -->
                <div class="flex gap-2">
                    <x-button wire:click="$set('jenis_kendaraan', '')" :variant="$jenis_kendaraan === '' ? 'primary' : 'outline'">
                        Semua
                    </x-button>

                    <x-button wire:click="$set('jenis_kendaraan', 'motor')" :variant="$jenis_kendaraan === 'motor' ? 'primary' : 'outline'">
                        Motor
                    </x-button>

                    <x-button wire:click="$set('jenis_kendaraan', 'mobil')" :variant="$jenis_kendaraan === 'mobil' ? 'primary' : 'outline'">
                        Mobil
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kendaraan Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">

        @if ($this->kendaraans->total())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($this->kendaraans as $kendaraan)
                    <x-card :title="$kendaraan->nama" :description="Str::limit($kendaraan->deskripsi, 80)" :image="$kendaraan->thumbnail_url" :price="'Rp ' . number_format($kendaraan->harga_sewa, 0, ',', '.')" status="Tersedia"
                        link="{{ route('user.detail', $kendaraan->id) }}" />
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
        @if ($this->kendaraans->hasPages())
            <div class="mt-12">
                {{ $this->kendaraans->links() }}
            </div>
        @endif
    </div>
</div>
