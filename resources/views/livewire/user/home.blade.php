<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\computed;
use function Livewire\Volt\state;
use App\Models\Kendaraan;
state([
    'merk' => '',
    'transmisi' => '',
]);
layout('layouts.user');
$merkOptions = computed(function () {
    return Kendaraan::query()->select('merk')->distinct()->orderBy('merk')->pluck('merk');
});
?>

<div>

    {{-- HERO SECTION                                   --}}

    <section class="relative w-full min-h-[calc(100vh-5rem)] flex items-center bg-slate-900 overflow-hidden">

        {{-- Background Image --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/image/Bacground.jpg') }}" alt="Melakuy Background"
                class="w-full h-full object-cover object-center opacity-60 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-12 pb-24">
            <div class="max-w-3xl">
                <span
                    class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 backdrop-blur-sm mb-6 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                    Melakuy, Pinjam dan Jalan
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                    Temukan Kendaraan <br class="hidden md:block" />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Petualangan
                        Barumu</span>
                </h1>
                <p class="text-lg text-slate-300 mb-10 max-w-2xl leading-relaxed">
                    Platform penyewaan kendaraan terpercaya. Rencanakan perjalanan Anda tanpa ribet dengan pilihan motor
                    dan mobil terbaik di seluruh Indonesia.
                </p>
            </div>

            {{-- ─── Search Box (Glassmorphism) ─── --}}
            <div
                class="w-full max-w-5xl rounded-2xl border border-white/15 bg-white/5 p-6 md:p-8 backdrop-blur-md shadow-2xl">
                <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-white">Cari Kendaraan Impianmu</h2>
                        <p class="text-sm text-slate-400 mt-1">Pilih kriteria sesuai kebutuhan perjalanan Anda.</p>
                    </div>
                    <div
                        class="inline-flex items-center gap-2 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-2.5 backdrop-blur-sm">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <div class="flex flex-col">
                            <span
                                class="text-[10px] uppercase tracking-widest text-emerald-300 font-semibold leading-none">Tersedia</span>
                            <span class="text-sm font-bold text-emerald-100 leading-none mt-1">100+ Unit</span>
                        </div>
                    </div>
                </div>

                {{-- Form GET → redirect ke halaman pencarian dengan filter di URL --}}
                <form action="{{ route('user.pencarian') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:items-end">

                        {{-- Brand Filter --}}
                        <div class="flex flex-col gap-2">
                            <label for="merk" class="text-sm font-semibold text-slate-300">Brand</label>
                            <div class="relative">
                                <select id="merk" name="merk"
                                    class="h-14 w-full appearance-none rounded-2xl border border-white/10 bg-slate-800/50 px-5 text-white transition-all duration-200 focus:bg-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 hover:bg-slate-800/80 cursor-pointer">
                                    <option value="" class="bg-slate-800 text-slate-300">Semua Brand</option>
                                    @foreach ($this->merkOptions as $merk)
                                        <option value="{{ $merk }}" class="bg-slate-800 text-white">
                                            {{ ucfirst($merk) }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Transmisi Filter --}}
                        <div class="flex flex-col gap-2">
                            <label for="transmisi" class="text-sm font-semibold text-slate-300">Transmisi</label>
                            <div class="relative">
                                <select id="transmisi" name="transmisi"
                                    class="h-14 w-full appearance-none rounded-2xl border border-white/10 bg-slate-800/50 px-5 text-white transition-all duration-200 focus:bg-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 hover:bg-slate-800/80 cursor-pointer">
                                    <option value="" @selected($transmisi === '')>Semua Transmisi</option>
                                    <option value="Manual" @selected($transmisi === 'Manual')>Manual</option>
                                    <option value="Matic" @selected($transmisi === 'Matic')>Matic / Otomatis</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Jenis Kendaraan Filter --}}
                        <div class="flex flex-col gap-2">
                            <label for="jenis_kendaraan" class="text-sm font-semibold text-slate-300">Jenis
                                Kendaraan</label>
                            <div class="relative">
                                <select id="jenis_kendaraan" name="jenis_kendaraan"
                                    class="h-14 w-full appearance-none rounded-2xl border border-white/10 bg-slate-800/50 px-5 text-white transition-all duration-200 focus:bg-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 hover:bg-slate-800/80 cursor-pointer">
                                    <option value="" class="bg-slate-800 text-slate-300">Semua Jenis</option>
                                    <option value="mobil" class="bg-slate-800 text-white">Mobil</option>
                                    <option value="motor" class="bg-slate-800 text-white">Motor</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="h-14 w-full rounded-2xl bg-indigo-600 px-6 font-bold text-white shadow-lg shadow-indigo-600/30 transition-all duration-300 hover:bg-indigo-500 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                            </svg>
                            Cari Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
