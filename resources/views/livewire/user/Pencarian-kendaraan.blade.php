<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\title;

layout('layouts.user');
title('Pencarian Kendaraan - Melakuy');

?>
    <div class="min-h-screen bg-slate-50 py-12 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Info -->
            <div class="text-center mb-10">
                <span
                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-4 border border-indigo-100">
                    Pencarian Spesifik
                </span>
                <h1 class="text-3xl font-black text-slate-900 sm:text-4xl tracking-tight">Temukan Kendaraan Impianmu</h1>
                <p class="mt-4 text-base text-slate-500 leading-relaxed max-w-2xl mx-auto">
                    Gunakan fitur pencarian lanjut di bawah ini untuk menemukan kendaraan yang paling sesuai dengan
                    kebutuhan dan budget perjalananmu.
                </p>
            </div>

            <!-- Search Form Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-indigo-500 to-violet-500"></div>

                <div class="p-6 md:p-10">
                    <form action="{{ route('user.daftar') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

                            <!-- Jenis Kendaraan -->
                            <div class="flex flex-col gap-2">
                                <label for="jenis_kendaraan"
                                    class="text-sm font-semibold text-slate-700 flex items-center gap-2">

                                    Jenis Kendaraan
                                </label>
                                <div class="relative">
                                    <select id="jenis_kendaraan" name="jenis_kendaraan"
                                        class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                        <option value="">Semua Jenis Kendaraan</option>
                                        <option value="mobil">Mobil</option>
                                        <option value="motor">Motor</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="flex flex-col gap-2">
                                <label for="brand" class="text-sm font-semibold text-slate-700 flex items-center gap-2">

                                    Brand Kendaraan
                                </label>
                                <div class="relative">
                                    <select id="brand" name="brand"
                                        class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                        <option value="">Semua Brand</option>
                                        <option value="honda">Honda</option>
                                        <option value="yamaha">Yamaha</option>
                                        <option value="toyota">Toyota</option>
                                        <option value="daihatsu">Daihatsu</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Transmisi -->
                            <div class="flex flex-col gap-2">
                                <label for="transmisi" class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Transmisi
                                </label>
                                <div class="relative">
                                    <select id="transmisi" name="transmisi"
                                        class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                        <option value="">Semua Transmisi</option>
                                        <option value="manual">Manual</option>
                                        <option value="matic">Matic / Otomatis</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Range Harga (Mockup Optional) -->
                            <div class="flex flex-col gap-2">
                                <label for="harga" class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Range Harga Sewa/Hari
                                </label>
                                <div class="relative">
                                    <select id="harga" name="harga"
                                        class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 text-slate-700 transition-all duration-200 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                                        <option value="">Semua Harga</option>
                                        <option value="<100k">&lt; Rp 100.000</option>
                                        <option value="100k-300k">Rp 100.000 - Rp 300.000</option>
                                        <option value=">300k">&gt; Rp 300.000</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <x-button type="reset" variant="outline" size="lg" class="hidden md:flex">
                                Reset Filter
                            </x-button>

                            <x-button type="submit" variant="primary" size="lg"
                                class="w-full md:w-auto min-w-[200px] flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                                </svg>
                                Mulai Pencarian
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

