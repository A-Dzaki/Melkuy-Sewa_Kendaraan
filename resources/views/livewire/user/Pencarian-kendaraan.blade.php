<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Models\Kendaraan;
use Illuminate\Support\Str;

layout('layouts.user');
title('Pencarian Kendaraan - Melakuy');

state([
    'jenis_kendaraan' => '',
    'brand'           => '',
    'transmisi'       => '',
    'harga'           => '',
    'sudahCari'       => false,
]);

$cari = function () {
    $this->sudahCari = true;
};

$reset = function () {
    $this->jenis_kendaraan = '';
    $this->brand           = '';
    $this->transmisi       = '';
    $this->harga           = '';
    $this->sudahCari       = false;
};

$hasil = computed(function () {
    if (! $this->sudahCari) {
        return collect();
    }

    $query = Kendaraan::with('images')->where('status', 'tersedia');

    if ($this->jenis_kendaraan) {
        $query->where('jenis', $this->jenis_kendaraan);
    }

    if ($this->brand) {
        $query->where('merk', 'like', '%' . $this->brand . '%');
    }

    if ($this->transmisi) {
        $query->where('transmisi', $this->transmisi);
    }

    if ($this->harga === '<100k') {
        $query->where('harga_sewa', '<', 100000);
    } elseif ($this->harga === '100k-300k') {
        $query->whereBetween('harga_sewa', [100000, 300000]);
    } elseif ($this->harga === '>300k') {
        $query->where('harga_sewa', '>', 300000);
    }

    return $query->latest()->get();
});

?>

<div class="min-h-screen bg-slate-50 py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-10 max-w-2xl mx-auto">
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-4 border border-indigo-100">
                Pencarian Spesifik
            </span>
            <h1 class="text-3xl font-black text-slate-900 sm:text-4xl tracking-tight">Temukan Kendaraan Impianmu</h1>
            <p class="mt-4 text-base text-slate-500 leading-relaxed">
                Gunakan fitur pencarian lanjut di bawah ini untuk menemukan kendaraan yang paling sesuai dengan kebutuhan dan budget perjalananmu.
            </p>
        </div>

        {{-- Search Form Card --}}
        <div class="max-w-4xl mx-auto mb-12">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-indigo-500 to-violet-500"></div>

                <div class="p-6 md:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

                        {{-- Jenis Kendaraan --}}
                        <div class="flex flex-col gap-2">
                            <label for="jenis_kendaraan" class="text-sm font-semibold text-slate-700">Jenis Kendaraan</label>
                            <div class="relative">
                                <select id="jenis_kendaraan" wire:model="jenis_kendaraan"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                    <option value="">Semua Jenis Kendaraan</option>
                                    <option value="mobil">Mobil</option>
                                    <option value="motor">Motor</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div class="flex flex-col gap-2">
                            <label for="brand" class="text-sm font-semibold text-slate-700">Brand Kendaraan</label>
                            <div class="relative">
                                <select id="brand" wire:model="brand"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                    <option value="">Semua Brand</option>
                                    <option value="honda">Honda</option>
                                    <option value="yamaha">Yamaha</option>
                                    <option value="toyota">Toyota</option>
                                    <option value="daihatsu">Daihatsu</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Transmisi --}}
                        <div class="flex flex-col gap-2">
                            <label for="transmisi" class="text-sm font-semibold text-slate-700">Transmisi</label>
                            <div class="relative">
                                <select id="transmisi" wire:model="transmisi"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                    <option value="">Semua Transmisi</option>
                                    <option value="manual">Manual</option>
                                    <option value="matic">Matic / Otomatis</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Range Harga --}}
                        <div class="flex flex-col gap-2">
                            <label for="harga" class="text-sm font-semibold text-slate-700">Range Harga Sewa/Hari</label>
                            <div class="relative">
                                <select id="harga" wire:model="harga"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                    <option value="">Semua Harga</option>
                                    <option value="<100k">&lt; Rp 100.000</option>
                                    <option value="100k-300k">Rp 100.000 - Rp 300.000</option>
                                    <option value=">300k">&gt; Rp 300.000</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between gap-4">
                        <button type="button" wire:click="reset"
                            class="hidden md:inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </button>

                        <button type="button" wire:click="cari" wire:loading.attr="disabled"
                            class="w-full md:w-auto min-w-[200px] h-12 px-8 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="cari" class="flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                                </svg>
                                Mulai Pencarian
                            </span>
                            <span wire:loading wire:target="cari" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mencari...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Hasil Pencarian ─── --}}
        @if ($this->sudahCari)
            <div wire:loading.class="opacity-50" wire:target="cari">

                {{-- Header hasil --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Hasil Pencarian</h2>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Ditemukan <span class="font-semibold text-indigo-600">{{ $this->hasil->count() }}</span> kendaraan
                            @if($this->jenis_kendaraan || $this->brand || $this->transmisi || $this->harga)
                                dengan filter yang dipilih
                            @endif
                        </p>
                    </div>
                    {{-- Filter aktif tags --}}
                    <div class="hidden md:flex items-center gap-2 flex-wrap justify-end">
                        @if($this->jenis_kendaraan)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                {{ ucfirst($this->jenis_kendaraan) }}
                                <button wire:click="$set('jenis_kendaraan', '')" class="ml-1 hover:text-indigo-900">×</button>
                            </span>
                        @endif
                        @if($this->brand)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                {{ ucfirst($this->brand) }}
                                <button wire:click="$set('brand', '')" class="ml-1 hover:text-indigo-900">×</button>
                            </span>
                        @endif
                        @if($this->transmisi)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                {{ ucfirst($this->transmisi) }}
                                <button wire:click="$set('transmisi', '')" class="ml-1 hover:text-indigo-900">×</button>
                            </span>
                        @endif
                        @if($this->harga)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                Harga: {{ $this->harga }}
                                <button wire:click="$set('harga', '')" class="ml-1 hover:text-indigo-900">×</button>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($this->hasil->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($this->hasil as $kendaraan)
                            <x-card
                                :title="$kendaraan->nama"
                                :description="Str::limit($kendaraan->deskripsi, 80)"
                                :image="$kendaraan->thumbnail_url"
                                :price="'Rp ' . number_format($kendaraan->harga_sewa, 0, ',', '.')"
                                status="Tersedia"
                                link="{{ route('user.detail', $kendaraan->id) }}" />
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-slate-200 border-dashed">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Kendaraan tidak ditemukan</h3>
                        <p class="text-slate-500 text-center max-w-sm mb-6">Coba ubah filter pencarian untuk menemukan kendaraan yang tersedia.</p>
                        <button wire:click="reset"
                            class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-500 transition-colors">
                            Reset & Coba Lagi
                        </button>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
