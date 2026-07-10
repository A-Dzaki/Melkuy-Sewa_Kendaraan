<?php

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\rules;
use function Livewire\Volt\mount;
use Illuminate\Support\Str;
use Carbon\Carbon;

layout('layouts.user');
title('Pesan Kendaraan - Melakuy');

state(['kendaraan', 'nama' => '', 'no_hp' => '', 'alamat' => '', 'tanggal_pinjam' => '', 'tanggal_kembali' => '', 'lama_sewa' => 0, 'total_harga' => 0]);

mount(function (Kendaraan $kendaraan) {
    $this->kendaraan = $kendaraan;
});

rules([
    'nama' => 'required|string|max:255',
    'no_hp' => 'required|string|max:20',
    'alamat' => 'required|string',
    'tanggal_pinjam' => 'required|date|after_or_equal:today',
    'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
]);

$calculateTotal = function () {
    if ($this->tanggal_pinjam && $this->tanggal_kembali) {
        $pinjam = Carbon::parse($this->tanggal_pinjam);
        $kembali = Carbon::parse($this->tanggal_kembali);

        if ($kembali->greaterThan($pinjam)) {
            $this->lama_sewa = $pinjam->diffInDays($kembali) ?: 1;
            $this->total_harga = $this->lama_sewa * $this->kendaraan->harga_sewa;
        } else {
            $this->lama_sewa = 0;
            $this->total_harga = 0;
        }
    }
};

$updatedTanggalPinjam = function () {
    $this->calculateTotal();
};

$updatedTanggalKembali = function () {
    $this->calculateTotal();
};

$save = function () {
    $validated = $this->validate();

    if ($this->kendaraan->status !== 'tersedia') {
        session()->flash('error', 'Maaf, kendaraan ini tidak tersedia untuk dipesan saat ini.');
        return;
    }

    $this->calculateTotal();

    // Generate random booking code e.g. BK-Y4M9X2
    $kode_booking = 'BK-' . strtoupper(Str::random(6));

    $peminjaman = Peminjaman::create([
        'kode_booking' => $kode_booking,
        'kendaraan_id' => $this->kendaraan->id,
        'nama' => $this->nama,
       
        'no_hp' => $this->no_hp,
     
        'alamat' => $this->alamat,
        'tanggal_pinjam' => $this->tanggal_pinjam,
        'tanggal_kembali' => $this->tanggal_kembali,
        'lama_sewa' => $this->lama_sewa,
        'total_harga' => $this->total_harga,
        'status' => 'pending', // initial status
    ]);

    // Update status kendaraan (optional: maybe wait until payment? For now let's set to dipesan)
    $this->kendaraan->update(['status' => 'dipesan']);

    session()->flash('success', 'Pesanan berhasil dibuat! Silakan lanjutkan ke pembayaran.');

    return $this->redirectRoute('user.pembayaran', ['peminjaman' => $peminjaman->id], navigate: true);
};

?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm mb-8">
            <a href="{{ route('user.detail', $kendaraan->id) }}" wire:navigate
                class="text-slate-500 hover:text-indigo-600 transition-colors">Kembali ke Detail Kendaraan</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">Form Pemesanan</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 bg-indigo-50/50">
                        <h2 class="text-xl font-bold text-slate-900">Data Pemesan</h2>
                        <p class="text-sm text-slate-500 mt-1">Isi formulir di bawah ini dengan lengkap dan benar.</p>
                    </div>

                    <form wire:submit="save" class="p-6 sm:p-8 space-y-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Nama --}}
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama
                                    Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                                <input type="text" id="nama" wire:model="nama" placeholder="Cth: Budi Santoso"
                                    class="w-full h-12 rounded-xl border border-slate-300 px-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white @error('nama') border-rose-500 ring-rose-500/10 @enderror">
                                @error('nama')
                                    <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- No HP --}}
                            <div>
                                <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">Nomor
                                    WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="text" id="no_hp" wire:model="no_hp" placeholder="Cth: 08123456789"
                                    class="w-full h-12 rounded-xl border border-slate-300 px-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white font-mono @error('no_hp') border-rose-500 ring-rose-500/10 @enderror">
                                @error('no_hp')
                                    <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-2">Alamat
                                Domisili <span class="text-rose-500">*</span></label>
                            <textarea id="alamat" wire:model="alamat" rows="3" placeholder="Alamat lengkap sesuai domisili saat ini"
                                class="w-full rounded-xl border border-slate-300 p-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white resize-none @error('alamat') border-rose-500 ring-rose-500/10 @enderror"></textarea>
                            @error('alamat')
                                <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="border-slate-200">

                        <h3 class="text-lg font-bold text-slate-900 pt-2">Jadwal Sewa</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Tanggal Pinjam --}}
                            <div>
                                <label for="tanggal_pinjam"
                                    class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai <span
                                        class="text-rose-500">*</span></label>
                                <input type="date" id="tanggal_pinjam" wire:model.live="tanggal_pinjam"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full h-12 rounded-xl border border-slate-300 px-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white @error('tanggal_pinjam') border-rose-500 ring-rose-500/10 @enderror">
                                @error('tanggal_pinjam')
                                    <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tanggal Kembali --}}
                            <div>
                                <label for="tanggal_kembali"
                                    class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Selesai <span
                                        class="text-rose-500">*</span></label>
                                <input type="date" id="tanggal_kembali" wire:model.live="tanggal_kembali"
                                    min="{{ $tanggal_pinjam ?: date('Y-m-d') }}"
                                    class="w-full h-12 rounded-xl border border-slate-300 px-4 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white @error('tanggal_kembali') border-rose-500 ring-rose-500/10 @enderror">
                                @error('tanggal_kembali')
                                    <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full h-14 rounded-xl bg-indigo-600 text-white font-bold text-lg shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2">

                                <span wire:loading.remove wire:target="save">
                                    Lanjutkan Pembayaran
                                </span>

                                <span wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-slate-900 rounded-3xl shadow-xl overflow-hidden sticky top-24 text-white">
                    <div class="p-6 border-b border-slate-800">
                        <h3 class="text-lg font-bold">Ringkasan Pesanan</h3>
                    </div>

                    <div class="p-6">
                        {{-- Vehicle Preview --}}
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-20 h-20 rounded-xl bg-slate-800 overflow-hidden shrink-0 border border-slate-700">
                                <img src="{{ $kendaraan->thumbnail_url }}" alt="{{ $kendaraan->nama }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-base leading-tight">{{ $kendaraan->nama }}</h4>
                                <p class="text-sm text-slate-400 mt-1">{{ $kendaraan->merk }} •
                                    {{ $kendaraan->tahun }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 text-sm border-t border-slate-800 pt-6">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Harga Sewa / Hari</span>
                                <span class="font-semibold">Rp
                                    {{ number_format($kendaraan->harga_sewa, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Durasi Sewa</span>
                                <span class="font-semibold">
                                    @if ($lama_sewa > 0)
                                        {{ $lama_sewa }} Hari
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-slate-800 mt-6 pt-6">
                            <div class="flex justify-between items-end">
                                <span class="text-slate-400 font-medium">Total Harga</span>
                                <span class="text-2xl font-black text-indigo-400">
                                    @if ($total_harga > 0)
                                        Rp {{ number_format($total_harga, 0, ',', '.') }}
                                    @else
                                        Rp 0
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-indigo-600/10 border-t border-indigo-500/20">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-indigo-200/80 leading-relaxed">
                                Pastikan data yang Anda masukkan benar. E-tiket / Bukti Pesanan akan dikirimkan ke Email
                                dan Nomor WhatsApp Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
