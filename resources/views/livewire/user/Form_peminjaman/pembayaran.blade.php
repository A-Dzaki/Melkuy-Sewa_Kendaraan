<?php

use App\Models\Peminjaman;
use App\Models\Pembayaran;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\state;
use function Livewire\Volt\rules;
use function Livewire\Volt\mount;
use function Livewire\Volt\usesFileUploads;

usesFileUploads();

layout('layouts.user');
title('Pembayaran - Melakuy');

state(['peminjaman', 'bukti_transfer']);

mount(function (Peminjaman $peminjaman) {
    $this->peminjaman = $peminjaman;
});

rules([
    'bukti_transfer' => 'required|image|max:5120', // Max 5MB
]);

$save = function () {
    $this->validate();

    if ($this->peminjaman->status !== 'menunggu_pembayaran') {
        session()->flash('error', 'Pesanan ini sudah dibayar atau dibatalkan.');
        return;
    }

    $path = $this->bukti_transfer->store('pembayaran', 'public');

    Pembayaran::create([
        'peminjaman_id' => $this->peminjaman->id,
        'metode' => 'transfer',
        'jumlah' => $this->peminjaman->total_harga,
        'bukti' => $path,
        'status' => 'menunggu_verifikasi',
        'dibayar_pada' => now(),
    ]);

    $this->peminjaman->update([
        'status' => 'menunggu_verifikasi'
    ]);

    session()->flash('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    
    return $this->redirectRoute('user.history.detail', ['peminjaman' => $this->peminjaman->id], navigate: true);
};

?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900">Selesaikan Pembayaran</h1>
            <p class="text-slate-500 mt-2">Segera lakukan pembayaran agar pesanan Anda tidak kadaluarsa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Order Summary --}}
            <div class="bg-indigo-600 rounded-3xl shadow-xl overflow-hidden text-white h-fit">
                <div class="p-8">
                    <p class="text-indigo-200 font-medium mb-1">Kode Booking</p>
                    <h2 class="text-3xl font-black font-mono tracking-wider">{{ $peminjaman->kode_booking }}</h2>
                    
                    <hr class="my-6 border-indigo-500/30">
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-indigo-200 text-sm">Kendaraan</p>
                            <p class="font-bold text-lg">{{ $peminjaman->kendaraan->nama }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">Jadwal Sewa</p>
                            <p class="font-bold">{{ $peminjaman->tanggal_pinjam->format('d M Y') }} - {{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                            <p class="text-sm text-indigo-300">{{ $peminjaman->lama_sewa }} Hari</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-indigo-500/30">
                        <p class="text-indigo-200 text-sm">Total Tagihan</p>
                        <p class="text-4xl font-black mt-1">Rp {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Instructions & Upload --}}
            <div class="space-y-6">
                {{-- Bank Accounts --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Transfer ke Rekening Berikut</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div class="w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-blue-800 border border-slate-200">BCA</div>
                            <div>
                                <p class="font-bold text-slate-900 tracking-wider">8732 1199 001</p>
                                <p class="text-xs text-slate-500 uppercase">A.n. Melakuy Rental</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div class="w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-orange-600 border border-slate-200">BNI</div>
                            <div>
                                <p class="font-bold text-slate-900 tracking-wider">0991 8827 66</p>
                                <p class="text-xs text-slate-500 uppercase">A.n. Melakuy Rental</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload Form --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Konfirmasi Pembayaran</h3>
                    
                    <form wire:submit="save">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Bukti Transfer</label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors relative">
                                <div class="space-y-1 text-center">
                                    @if ($bukti_transfer)
                                        <div class="mb-4 flex justify-center">
                                            <div class="relative w-32 h-32 rounded-xl overflow-hidden shadow-sm border border-slate-200">
                                                <img src="{{ $bukti_transfer->temporaryUrl() }}" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @else
                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                    
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2">
                                            <span>Upload a file</span>
                                            <input id="file-upload" wire:model="bukti_transfer" name="file-upload" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                            @error('bukti_transfer') <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                            class="w-full h-12 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2"
                            {{ empty($bukti_transfer) ? 'disabled' : '' }}
                            @class(['opacity-50 cursor-not-allowed' => empty($bukti_transfer)])>
                            
                            <span wire:loading.remove wire:target="save">Kirim Konfirmasi</span>
                            <span wire:loading wire:target="save" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengunggah...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>