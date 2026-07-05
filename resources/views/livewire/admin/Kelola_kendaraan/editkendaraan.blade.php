<?php

use App\Models\Kendaraan;
use function Livewire\Volt\{state, layout, title, rules, mount};

layout('layouts.admin');
title('Edit Kendaraan');

state([
    'kendaraan' => null,
    'jenis' => '',
    'merk' => '',
    'nama' => '',
    'tahun' => '',
    'warna' => '',
    'transmisi' => '',
    'harga_sewa' => '',
    'status' => '',
    'deskripsi' => '',
]);

rules([
    'jenis' => 'required|in:motor,mobil',
    'merk' => 'required|string|max:100',
    'nama' => 'required|string|max:150',
    'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
    'warna' => 'required|string|max:50',
    'transmisi' => 'required|in:Manual,Matic',
    'harga_sewa' => 'required|numeric|min:10000',
    'status' => 'required|in:tersedia,dipesan,dipinjam,maintenance',
    'deskripsi' => 'nullable|string|max:1000',
]);

mount(function (Kendaraan $kendaraan) {
    $this->kendaraan = $kendaraan;
    $this->jenis = $kendaraan->jenis;
    $this->merk = $kendaraan->merk;
    $this->nama = $kendaraan->nama;
    $this->tahun = $kendaraan->tahun;
    $this->warna = $kendaraan->warna;
    $this->transmisi = $kendaraan->transmisi;
    $this->harga_sewa = $kendaraan->harga_sewa;
    $this->status = $kendaraan->status;
    $this->deskripsi = $kendaraan->deskripsi;
});

$update = function () {
    $validated = $this->validate();

    $this->kendaraan->update($validated);

    session()->flash('success', "Kendaraan \"{$this->nama}\" berhasil diperbarui.");

    $this->redirect(route('admin.kendaraan'), navigate: true);
};

?>

<div>
    <div class="flex flex-col gap-6 max-w-4xl">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.kendaraan') }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">Kendaraan</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">Edit: {{ $kendaraan->nama }}</span>
        </nav>

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Kendaraan</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui informasi kendaraan rental Anda.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <form wire:submit="update" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="p-6 sm:p-8 space-y-6">

                {{-- Kode Kendaraan (Readonly) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Kendaraan</label>
                    <input type="text" value="{{ $kendaraan->kode_kendaraan }}" readonly
                        class="h-10 w-full max-w-xs px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-500 font-mono cursor-not-allowed">
                </div>

                <div class="h-px bg-slate-100"></div>

                {{-- Grid: Jenis & Transmisi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kendaraan <span class="text-rose-500">*</span></label>
                        <select id="jenis" wire:model.live="jenis"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('jenis') !border-rose-400 !ring-rose-500/10 @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                        </select>
                        @error('jenis') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="transmisi" class="block text-sm font-semibold text-slate-700 mb-2">Transmisi <span class="text-rose-500">*</span></label>
                        <select id="transmisi" wire:model.live="transmisi"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('transmisi') !border-rose-400 !ring-rose-500/10 @enderror">
                            <option value="">Pilih Transmisi</option>
                            <option value="Manual">Manual</option>
                            <option value="Matic">Matic</option>
                        </select>
                        @error('transmisi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Grid: Merk & Nama --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="merk" class="block text-sm font-semibold text-slate-700 mb-2">Merk <span class="text-rose-500">*</span></label>
                        <input type="text" id="merk" wire:model.live="merk" placeholder="Contoh: Honda, Toyota"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400 transition-all @error('merk') !border-rose-400 !ring-rose-500/10 @enderror">
                        @error('merk') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kendaraan <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama" wire:model.live="nama" placeholder="Contoh: Brio Satya"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400 transition-all @error('nama') !border-rose-400 !ring-rose-500/10 @enderror">
                        @error('nama') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Grid: Tahun, Warna, Harga --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="tahun" class="block text-sm font-semibold text-slate-700 mb-2">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" wire:model.live="tahun" min="2000" max="{{ date('Y') + 1 }}"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('tahun') !border-rose-400 !ring-rose-500/10 @enderror">
                        @error('tahun') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="warna" class="block text-sm font-semibold text-slate-700 mb-2">Warna <span class="text-rose-500">*</span></label>
                        <input type="text" id="warna" wire:model.live="warna"
                            class="h-10 w-full px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('warna') !border-rose-400 !ring-rose-500/10 @enderror">
                        @error('warna') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="harga_sewa" class="block text-sm font-semibold text-slate-700 mb-2">Harga Sewa/Hari <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-slate-400 font-medium">Rp</span>
                            <input type="number" id="harga_sewa" wire:model.live="harga_sewa" min="10000" step="1000"
                                class="h-10 w-full pl-10 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('harga_sewa') !border-rose-400 !ring-rose-500/10 @enderror">
                        </div>
                        @error('harga_sewa') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-rose-500">*</span></label>
                    <select id="status" wire:model.live="status"
                        class="h-10 w-full max-w-xs px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all @error('status') !border-rose-400 !ring-rose-500/10 @enderror">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipesan">Dipesan</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" wire:model.live="deskripsi" rows="4" placeholder="Deskripsi singkat (opsional)..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-400 transition-all resize-none @error('deskripsi') !border-rose-400 !ring-rose-500/10 @enderror"></textarea>
                    <p class="mt-1 text-xs text-slate-400">Maksimal 1000 karakter. {{ strlen($deskripsi ?? '') }}/1000</p>
                    @error('deskripsi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="px-6 sm:px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                <x-button variant="outline" href="{{ route('admin.kendaraan') }}" wire:navigate>
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    <span wire:loading.remove wire:target="update">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </span>
                    <span wire:loading wire:target="update" class="inline-flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </x-button>
            </div>
        </form>
    </div>
</div>
