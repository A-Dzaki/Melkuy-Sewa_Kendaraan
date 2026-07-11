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

state(['peminjaman', 'bukti_transfer', 'metode_pembayaran' => 'transfer', 'sudahDibayar' => false]);

mount(function (Peminjaman $peminjaman) {
    $this->peminjaman = $peminjaman;
});

$save = function () {
    // Guard: cegah double submit
    if ($this->sudahDibayar) {
        return;
    }

    // Validasi berdasarkan metode
    if ($this->metode_pembayaran === 'transfer') {
        $this->validate([
            'bukti_transfer' => ['required', 'image', 'max:5120'],
            'metode_pembayaran' => ['required', 'in:transfer,cash'],
        ]);
    } else {
        $this->validate([
            'metode_pembayaran' => ['required', 'in:transfer,cash'],
        ]);
    }

    // Refresh model dari database untuk pastikan data terbaru
    $this->peminjaman->refresh();

    if (!in_array($this->peminjaman->status, ['pending', 'menunggu_pembayaran'])) {
        session()->flash('error', 'Pesanan ini sudah dibayar atau dibatalkan.');
        return;
    }

    try {
        $path = '';
        if ($this->metode_pembayaran === 'transfer' && $this->bukti_transfer) {
            $path = $this->bukti_transfer->store('pembayaran', 'public');
        }

        $statusPembayaran = 'pending';

        Pembayaran::create([
            'peminjaman_id' => $this->peminjaman->id,
            'metode' => $this->metode_pembayaran,
            'jumlah' => $this->peminjaman->total_harga,
            'bukti' => $path,
            'status' => $statusPembayaran,
            'dibayar_pada' => now(),
        ]);

        $statusPeminjaman = 'pending';

        $this->peminjaman->update([
            'status' => $statusPeminjaman,
        ]);

        $this->sudahDibayar = true;

        $successMessage = $this->metode_pembayaran === 'cash'
            ? 'Pemesanan tunai berhasil dikonfirmasi! Mohon siapkan uang tunai saat pengambilan kendaraan.'
            : 'Bukti pembayaran berhasil diunggah dan diverifikasi! Mohon tunggu instruksi pengambilan kendaraan.';

        $this->dispatch('pembayaran-sukses', 
            message: $successMessage,
            metode: $this->metode_pembayaran,
        );
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' di baris ' . $e->getLine());
    }
};

?>

<div class="bg-slate-50 min-h-screen py-12" x-data="paymentSuccess()" @pembayaran-sukses.window="showSuccess($event.detail)">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900">Selesaikan Pembayaran</h1>
            <p class="text-slate-500 mt-2">Segera lakukan pembayaran agar pesanan Anda tidak kadaluarsa.</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

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
                            <p class="font-bold">{{ $peminjaman->tanggal_pinjam->format('d M Y') }} -
                                {{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                            <p class="text-sm text-indigo-300">{{ $peminjaman->lama_sewa }} Hari</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-indigo-500/30">
                        <p class="text-indigo-200 text-sm">Total Tagihan</p>
                        <p class="text-4xl font-black mt-1">Rp
                            {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Method Selection & Instructions --}}
            <div class="space-y-6">
                {{-- Pilih Metode Pembayaran --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Pilih Metode Pembayaran</h3>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Transfer --}}
                        <label
                            class="relative cursor-pointer rounded-2xl border-2 p-4 flex flex-col items-center gap-3 transition-all hover:shadow-md
                                {{ $metode_pembayaran === 'transfer' ? 'border-indigo-600 bg-indigo-50 shadow-md' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <input type="radio" wire:model.live="metode_pembayaran" value="transfer" class="sr-only">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $metode_pembayaran === 'transfer' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <span class="font-bold text-sm {{ $metode_pembayaran === 'transfer' ? 'text-indigo-700' : 'text-slate-600' }}">Transfer Bank</span>
                            @if ($metode_pembayaran === 'transfer')
                                <div class="absolute top-2 right-2 w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @endif
                        </label>

                        {{-- Cash / Tunai --}}
                        <label
                            class="relative cursor-pointer rounded-2xl border-2 p-4 flex flex-col items-center gap-3 transition-all hover:shadow-md
                                {{ $metode_pembayaran === 'cash' ? 'border-emerald-600 bg-emerald-50 shadow-md' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <input type="radio" wire:model.live="metode_pembayaran" value="cash" class="sr-only">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $metode_pembayaran === 'cash' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="font-bold text-sm {{ $metode_pembayaran === 'cash' ? 'text-emerald-700' : 'text-slate-600' }}">Cash / Tunai</span>
                            @if ($metode_pembayaran === 'cash')
                                <div class="absolute top-2 right-2 w-5 h-5 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @endif
                        </label>
                    </div>
                    @error('metode_pembayaran')
                        <span class="text-xs text-rose-500 font-semibold mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Transfer: Bank Accounts --}}
                @if ($metode_pembayaran === 'transfer')
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Transfer ke Rekening Berikut</h3>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div
                                class="w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-blue-800 border border-slate-200">
                                BCA</div>
                            <div>
                                <p class="font-bold text-slate-900 tracking-wider">8732 1199 001</p>
                                <p class="text-xs text-slate-500 uppercase">A.n. Melakuy Rental</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div
                                class="w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-orange-600 border border-slate-200">
                                BNI</div>
                            <div>
                                <p class="font-bold text-slate-900 tracking-wider">0991 8827 66</p>
                                <p class="text-xs text-slate-500 uppercase">A.n. Melakuy Rental</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Cash: Info --}}
                @if ($metode_pembayaran === 'cash')
                <div class="bg-emerald-50 rounded-3xl shadow-sm border border-emerald-200 p-6">
                    <h3 class="text-lg font-bold text-emerald-900 mb-3">Pembayaran Tunai</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">Bayar langsung saat pengambilan kendaraan</p>
                                <p class="text-xs text-emerald-600 mt-1">Siapkan uang tunai sesuai total tagihan. Pembayaran dilakukan di lokasi rental.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">Total yang harus dibayar</p>
                                <p class="text-lg font-black text-emerald-700 mt-0.5">Rp {{ number_format($peminjaman->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Upload / Confirm Form --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Konfirmasi Pembayaran</h3>

                    <form wire:submit="save">
                        {{-- Upload bukti transfer (hanya untuk metode transfer) --}}
                        @if ($metode_pembayaran === 'transfer')
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Bukti Transfer</label>

                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors relative">
                                <div class="space-y-1 text-center">
                                    @if ($bukti_transfer)
                                        <div class="mb-4 flex justify-center">
                                            <div
                                                class="relative w-32 h-32 rounded-xl overflow-hidden shadow-sm border border-slate-200">
                                                <img src="{{ $bukti_transfer->temporaryUrl() }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @else
                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif

                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="file-upload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2">
                                            <span>Upload a file</span>
                                            <input id="file-upload" wire:model="bukti_transfer" name="file-upload"
                                                type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                            @error('bukti_transfer')
                                <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <button type="submit"
                            class="w-full h-12 rounded-xl text-white font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2
                                {{ $metode_pembayaran === 'cash' ? 'bg-emerald-600 shadow-emerald-600/30 hover:bg-emerald-500' : 'bg-indigo-600 shadow-indigo-600/30 hover:bg-indigo-500' }}"
                            @if($metode_pembayaran === 'transfer' && empty($bukti_transfer)) disabled @endif
                            @class(['opacity-50 cursor-not-allowed' => $metode_pembayaran === 'transfer' && empty($bukti_transfer)])>

                            <span wire:loading.remove wire:target="save">
                                {{ $metode_pembayaran === 'cash' ? 'Konfirmasi Bayar Tunai' : 'Kirim Konfirmasi' }}
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
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SUCCESS OVERLAY ANIMATION --}}
    {{-- ═══════════════════════════════════════════ --}}
    <template x-teleport="body">
        <div x-show="visible" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] flex items-center justify-center" style="display: none;">
            {{-- Backdrop with blur --}}
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            {{-- Content --}}
            <div x-show="visible" x-transition:enter="transition ease-out duration-500 delay-150" x-transition:enter-start="opacity-0 scale-75 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative z-10 max-w-md w-full mx-4">
                <div class="bg-white rounded-3xl shadow-2xl p-10 text-center relative overflow-hidden">

                    {{-- Animated particles --}}
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        <template x-if="visible">
                            <div>
                                <div class="payment-particle" style="--x: 20%; --delay: 0s; --color: #818cf8;"></div>
                                <div class="payment-particle" style="--x: 40%; --delay: 0.2s; --color: #34d399;"></div>
                                <div class="payment-particle" style="--x: 60%; --delay: 0.4s; --color: #fbbf24;"></div>
                                <div class="payment-particle" style="--x: 80%; --delay: 0.1s; --color: #f472b6;"></div>
                                <div class="payment-particle" style="--x: 30%; --delay: 0.3s; --color: #60a5fa;"></div>
                                <div class="payment-particle" style="--x: 70%; --delay: 0.5s; --color: #a78bfa;"></div>
                                <div class="payment-particle-square" style="--x: 25%; --delay: 0.15s; --color: #34d399;"></div>
                                <div class="payment-particle-square" style="--x: 50%; --delay: 0.35s; --color: #818cf8;"></div>
                                <div class="payment-particle-square" style="--x: 75%; --delay: 0.55s; --color: #fbbf24;"></div>
                            </div>
                        </template>
                    </div>

                    {{-- Checkmark Circle --}}
                    <div class="relative mx-auto mb-6" style="width: 100px; height: 100px;">
                        {{-- Pulse rings --}}
                        <template x-if="visible">
                            <div>
                                <div class="absolute inset-0 rounded-full payment-pulse-ring" :class="metode === 'cash' ? 'bg-emerald-400' : 'bg-indigo-400'"></div>
                                <div class="absolute inset-0 rounded-full payment-pulse-ring" :class="metode === 'cash' ? 'bg-emerald-400' : 'bg-indigo-400'" style="animation-delay: 0.4s;"></div>
                            </div>
                        </template>

                        {{-- Circle --}}
                        <div class="absolute inset-0 rounded-full flex items-center justify-center shadow-lg" :class="metode === 'cash' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : 'bg-gradient-to-br from-indigo-400 to-indigo-600'">
                            {{-- Animated SVG Checkmark --}}
                            <svg class="w-12 h-12 text-white" viewBox="0 0 52 52" fill="none">
                                <template x-if="visible">
                                    <path class="payment-checkmark-draw" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" d="M14 27 L22 35 L38 17" />
                                </template>
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <h2 class="text-2xl font-black text-slate-900 mb-2" x-show="visible" x-transition:enter="transition ease-out duration-400 delay-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        Pembayaran Berhasil! 🎉
                    </h2>

                    {{-- Message --}}
                    <p class="text-slate-500 text-sm leading-relaxed mb-6" x-show="visible" x-text="message" x-transition:enter="transition ease-out duration-400 delay-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"></p>

                    {{-- Metode badge --}}
                    <div x-show="visible" x-transition:enter="transition ease-out duration-300 delay-900" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="mb-6">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold" :class="metode === 'cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700'">
                            <template x-if="metode === 'cash'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </template>
                            <template x-if="metode !== 'cash'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </template>
                            <span x-text="metode === 'cash' ? 'Tunai / Cash' : 'Transfer Bank'"></span>
                        </span>
                    </div>

                    {{-- Progress bar redirect --}}
                    <div x-show="visible" x-transition:enter="transition ease-out duration-300 delay-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <p class="text-xs text-slate-400 mb-2">Mengalihkan ke dashboard dalam <span x-text="countdown"></span> detik...</p>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full payment-progress-bar" :class="metode === 'cash' ? 'bg-emerald-500' : 'bg-indigo-500'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Success Animation Styles --}}
    <style>
        /* Checkmark draw animation */
        .payment-checkmark-draw {
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: payment-draw-check 0.6s ease-out 0.5s forwards;
        }
        @keyframes payment-draw-check {
            to { stroke-dashoffset: 0; }
        }

        /* Pulse rings */
        .payment-pulse-ring {
            opacity: 0;
            animation: payment-pulse 2s ease-out 0.3s infinite;
        }
        @keyframes payment-pulse {
            0% { transform: scale(1); opacity: 0.4; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        /* Confetti particles (circles) */
        .payment-particle {
            position: absolute;
            left: var(--x);
            top: 50%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--color);
            animation: payment-particle-fly 1.2s ease-out var(--delay) forwards;
            opacity: 0;
        }
        .payment-particle-square {
            position: absolute;
            left: var(--x);
            top: 50%;
            width: 6px;
            height: 6px;
            border-radius: 2px;
            background: var(--color);
            animation: payment-particle-fly-alt 1.4s ease-out var(--delay) forwards;
            opacity: 0;
        }
        @keyframes payment-particle-fly {
            0% { transform: translate(0, 0) scale(0); opacity: 0; }
            20% { opacity: 1; transform: translate(0, 0) scale(1); }
            100% { transform: translate(calc(var(--x) - 50%), -180px) rotate(360deg) scale(0); opacity: 0; }
        }
        @keyframes payment-particle-fly-alt {
            0% { transform: translate(0, 0) scale(0) rotate(0deg); opacity: 0; }
            20% { opacity: 1; transform: translate(0, 0) scale(1) rotate(0deg); }
            100% { transform: translate(calc(50% - var(--x)), -220px) rotate(540deg) scale(0); opacity: 0; }
        }

        /* Progress bar */
        .payment-progress-bar {
            animation: payment-progress 3s linear forwards;
        }
        @keyframes payment-progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    {{-- Alpine.js Component --}}
    <script>
        function paymentSuccess() {
            return {
                visible: false,
                message: '',
                metode: 'transfer',
                countdown: 3,
                countdownInterval: null,

                showSuccess(detail) {
                    // Support both Livewire 3 event formats
                    this.message = detail.message || detail[0]?.message || '';
                    this.metode = detail.metode || detail[0]?.metode || 'transfer';
                    this.visible = true;
                    this.countdown = 3;

                    // Countdown timer
                    this.countdownInterval = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(this.countdownInterval);
                            window.location.href = '{{ route("home") }}';
                        }
                    }, 1000);
                },

                destroy() {
                    if (this.countdownInterval) clearInterval(this.countdownInterval);
                }
            }
        }
    </script>
</div>
