<?php

use App\Models\Peminjaman;
use function Livewire\Volt\layout;
use function Livewire\Volt\state;
use function Livewire\Volt\mount;

layout('layouts.admin');

state(['peminjaman']);

mount(function (Peminjaman $peminjaman) {
    $this->peminjaman = $peminjaman->load(['kendaraan', 'pembayaran']);
});

$approve = function () {
    $this->peminjaman->update(['status' => 'approved']);
    session()->flash('success', 'Pesanan berhasil disetujui.');
};

$reject = function () {
    $this->peminjaman->update(['status' => 'cancelled']);
    session()->flash('success', 'Pesanan berhasil ditolak.');
};

?>
<div class="flex flex-col gap-6" x-data="{ openConfirm: false, action: null, message: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Detail Pesanan: {{ $peminjaman->kode_booking }}</h2>
            <p class="text-sm text-slate-500 mt-1">Review informasi pesanan sebelum menyetujui atau menolak.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button href="{{ route('admin.pemesanan') }}" variant="outline">
                Kembali
            </x-button>
            @if($peminjaman->status === 'pending')
                <x-button @click="openConfirm = true; action = 'reject'; message = 'Apakah Anda yakin ingin menolak pesanan ini?'" variant="danger">
                    Tolak
                </x-button>
                <x-button @click="openConfirm = true; action = 'approve'; message = 'Apakah Anda yakin ingin menyetujui pesanan ini?'" variant="primary">
                    Setujui
                </x-button>
            @endif
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
            class="flex items-center justify-between bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 shadow-sm"
        >
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700 transition-colors p-1 rounded-md hover:bg-green-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Content Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Penyewa -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Penyewa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Nama Lengkap</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->nama }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">No HP / WhatsApp</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->no_hp }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-sm text-slate-500 mb-1">Alamat</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Sewa -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Sewa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Tanggal Pinjam</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Tanggal Kembali</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Lama Sewa</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->lama_sewa }} Hari</span>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Total Biaya</span>
                        <span class="block font-medium text-slate-900">{{ $peminjaman->formatted_total_harga }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Status</span>
                        <x-badge type="{{ $peminjaman->status_badge_type }}">
                            {{ $peminjaman->status_label }}
                        </x-badge>
                    </div>
                    <div>
                        <span class="block text-sm text-slate-500 mb-1">Status Pembayaran</span>
                        @if($peminjaman->pembayaran)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md bg-green-50 text-green-700 border border-green-200">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Lunas ({{ ucfirst($peminjaman->pembayaran->metode) }})
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md bg-slate-50 text-slate-600 border border-slate-200">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kendaraan -->
        <div class="flex flex-col gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Kendaraan</h3>
                @if($peminjaman->kendaraan)
                    <div class="aspect-video bg-slate-100 rounded-xl overflow-hidden mb-4">
                        <img src="{{ $peminjaman->kendaraan->thumbnail_url }}" alt="{{ $peminjaman->kendaraan->nama }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col gap-2">
                        <h4 class="font-bold text-slate-900">{{ $peminjaman->kendaraan->nama }}</h4>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="px-2 py-1 bg-slate-100 rounded-md">{{ ucfirst($peminjaman->kendaraan->jenis) }}</span>
                            <span class="px-2 py-1 bg-slate-100 rounded-md">{{ ucfirst($peminjaman->kendaraan->transmisi) }}</span>
                            <span class="px-2 py-1 bg-slate-100 rounded-md">{{ $peminjaman->kendaraan->tahun }}</span>
                        </div>
                        <div class="text-sm mt-2">
                            <span class="text-slate-500">Harga per hari: </span>
                            <span class="font-semibold text-slate-900">{{ $peminjaman->kendaraan->formatted_harga }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-slate-500">Kendaraan tidak ditemukan atau telah dihapus.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <template x-teleport="body">
        <div x-show="openConfirm" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div @click.outside="openConfirm = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all"
                 x-show="openConfirm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-full"
                         :class="action === 'reject' ? 'bg-rose-100 text-rose-600' : 'bg-indigo-100 text-indigo-600'">
                        <svg x-show="action === 'reject'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <svg x-show="action === 'approve'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" x-text="action === 'reject' ? 'Konfirmasi Penolakan' : 'Konfirmasi Persetujuan'"></h3>
                        <p class="text-sm text-slate-500 mt-1" x-text="message"></p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="openConfirm = false" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200">
                        Batal
                    </button>
                    <button @click="openConfirm = false; if(action === 'reject') { $wire.reject() } else { $wire.approve() }"
                            :class="action === 'reject' ? 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500 shadow-rose-500/30' : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 shadow-indigo-500/30'"
                            class="px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
