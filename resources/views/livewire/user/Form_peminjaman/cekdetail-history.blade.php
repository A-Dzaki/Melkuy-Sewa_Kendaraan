<?php

use App\Models\Peminjaman;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\mount;

layout('layouts.user');
title('Detail Pesanan - Melakuy');

state(['peminjaman', 'statusConfig']);

mount(function (Peminjaman $peminjaman) {
    $this->peminjaman = $peminjaman;
    $this->statusConfig = match ($this->peminjaman->status) {
        'pending' => [
            'label' => 'Menunggu Pembayaran',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-700',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'menunggu_pembayaran' => [
            'label' => 'Belum Dibayar',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-700',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'menunggu_verifikasi' => [
            'label' => 'Menunggu Verifikasi',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-700',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'disetujui' => [
            'label' => 'Disetujui',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-700',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'berjalan' => [
            'label' => 'Berjalan',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-700',
            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
        ],
        'selesai' => [
            'label' => 'Selesai',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-700',
            'icon' => 'M5 13l4 4L19 7',
        ],
        'dibatalkan' => [
            'label' => 'Dibatalkan',
            'bg' => 'bg-rose-100',
            'text' => 'text-rose-700',
            'icon' => 'M6 18L18 6M6 6l12 12',
        ],
        default => [
            'label' => 'Unknown',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-700',
            'icon' => '',
        ],
    };
});

?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-emerald-800 font-bold text-lg">Pemesanan Berhasil</h3>
                    <p class="text-emerald-600 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden relative">
            {{-- Ticket Header --}}
            <div class="bg-orange-600 p-8 text-white flex flex-col sm:flex-row justify-between items-center gap-6">
                <div>
                    <p class="text-indigo-200 text-sm font-medium uppercase tracking-wider mb-1">Kode Booking</p>
                    <h2 class="text-4xl font-black font-mono tracking-widest">{{ $this->peminjaman->kode_booking }}</h2>
                </div>


                <div
                    class="px-4 py-2 rounded-xl {{ $this->statusConfig['bg'] }} {{ $this->statusConfig['text'] }} flex items-center gap-2 font-bold shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $this->statusConfig['icon'] }}" />
                    </svg>
                    {{ $this->statusConfig['label'] }}
                </div>
            </div>

            {{-- Decorative circles --}}
            <div
                class="absolute w-8 h-8 bg-slate-50 rounded-full -left-4 top-[110px] shadow-inner border-r border-slate-200">
            </div>
            <div
                class="absolute w-8 h-8 bg-slate-50 rounded-full -right-4 top-[110px] shadow-inner border-l border-slate-200">
            </div>
            <div class="absolute w-full border-t-[3px] border-dashed border-slate-200 top-[125px]"></div>

            {{-- Body --}}
            <div class="p-8 pt-12">
                {{-- Vehicle Info --}}
                <div class="flex items-center gap-6 mb-10">
                    <div
                        class="w-24 h-24 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 shadow-sm">
                        <img src="{{ $peminjaman->kendaraan->thumbnail_url }}" alt="{{ $peminjaman->kendaraan->nama }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-1 block">{{ $peminjaman->kendaraan->jenis }}</span>
                        <h3 class="text-2xl font-black text-slate-900">{{ $peminjaman->kendaraan->nama }}</h3>
                        <p class="text-slate-500 font-medium">{{ $peminjaman->kendaraan->merk }} •
                            {{ $peminjaman->kendaraan->tahun }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-10">
                    <div>
                        <p class="text-sm text-slate-500 font-medium mb-1">Nama Penyewa</p>
                        <p class="font-bold text-slate-900 text-lg">{{ $peminjaman->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-medium mb-1">Nomor Kontak (WA)</p>
                        <p class="font-bold text-slate-900 text-lg font-mono">{{ $peminjaman->no_hp }}</p>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-2 p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div>
                            <p class="text-sm text-slate-500 font-medium mb-1">Tanggal Mulai</p>
                            <p class="font-bold text-slate-900">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 font-medium mb-1">Tanggal Selesai</p>
                            <p class="font-bold text-slate-900">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Price --}}
                <div
                    class="p-6 rounded-2xl bg-indigo-50 border border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <p class="text-indigo-800 font-medium">Total Pembayaran</p>
                        <p class="text-sm text-indigo-600/80 mt-1">Durasi Sewa: {{ $peminjaman->lama_sewa }} Hari</p>
                    </div>
                    <p class="text-3xl font-black text-indigo-700">Rp
                        {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</p>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">

                    @if (in_array($peminjaman->status, ['pending', 'menunggu_pembayaran']))
                        <a href="{{ route('user.pembayaran', $peminjaman->id) }}" wire:navigate
                            class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 transition-all text-center flex items-center justify-center gap-2">
                            Selesaikan Pembayaran
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('home') }}" wire:navigate
                            class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 transition-all text-center">
                            Kembali ke Beranda
                        </a>
                    @endif
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-400">Harap simpan kode booking ini. Gunakan saat mengambil kendaraan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background: white !important;
        }

        nav,
        footer,
        .bg-slate-50 {
            background: white !important;
        }

        button,
        a[wire\:navigate] {
            display: none !important;
        }

        .shadow-xl {
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
        }
    }
</style>
