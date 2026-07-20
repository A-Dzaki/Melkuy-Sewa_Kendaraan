<?php

use App\Models\Kendaraan;
use App\Models\KendaraanImage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{state, layout, title, mount, computed, uses};

layout('layouts.admin');
title('Kelola Gambar Kendaraan');

uses([WithFileUploads::class]);

state([
    'kendaraan' => null,
    'photos' => [],
    'deleteImageId' => null,
]);

mount(function (Kendaraan $kendaraan) {
    $this->kendaraan = $kendaraan->load('images');
});

$images = computed(function () {
    return $this->kendaraan->images()->orderByDesc('thumbnail')->latest()->get();
});

$uploadPhotos = function () {
    $this->validate([
        'photos' => 'required|array|min:1',
        'photos.*' => 'image|mimes:jpeg,jpg,png|max:2048',
    ], [
        'photos.required' => 'Pilih minimal satu gambar.',
        'photos.*.image' => 'File harus berupa gambar.',
        'photos.*.mimes' => 'Format yang didukung: JPEG/PNG.',
        'photos.*.max' => 'Ukuran maksimal per gambar adalah 2MB.',
    ]);

    $currentCount = $this->kendaraan->images()->count();
    $maxImages = 5;
    $allowedNew = $maxImages - $currentCount;

    if ($allowedNew <= 0) {
        session()->flash('error', "Maksimal {$maxImages} gambar per kendaraan. Hapus gambar lama terlebih dahulu.");
        $this->photos = [];
        return;
    }

    $uploaded = 0;
    foreach (array_slice($this->photos, 0, $allowedNew) as $photo) {
        $path = $photo->store('kendaraan', 'public');

        $this->kendaraan->images()->create([
            'image' => $path,
            'thumbnail' => $currentCount === 0 && $uploaded === 0, // Set pertama sebagai thumbnail jika belum ada gambar
        ]);
        $uploaded++;
    }

    $this->photos = [];
    $this->kendaraan->refresh();

    session()->flash('success', "{$uploaded} gambar berhasil diupload.");
};

$setThumbnail = function (int $imageId) {
    // Reset semua thumbnail
    $this->kendaraan->images()->update(['thumbnail' => false]);

    // Set yang dipilih
    KendaraanImage::findOrFail($imageId)->update(['thumbnail' => true]);

    $this->kendaraan->refresh();
    session()->flash('success', 'Thumbnail berhasil diperbarui.');
};

$confirmDeleteImage = function (int $imageId) {
    $this->deleteImageId = $imageId;
};

$cancelDeleteImage = function () {
    $this->deleteImageId = null;
};

$deleteImage = function () {
    if ($this->deleteImageId) {
        $image = KendaraanImage::findOrFail($this->deleteImageId);

        Storage::disk('public')->delete($image->image);

        $wasThumbnail = $image->thumbnail;
        $image->delete();

        // Jika yang dihapus adalah thumbnail, set gambar pertama sebagai thumbnail baru
        if ($wasThumbnail) {
            $firstImage = $this->kendaraan->images()->first();
            if ($firstImage) {
                $firstImage->update(['thumbnail' => true]);
            }
        }

        $this->deleteImageId = null;
        $this->kendaraan->refresh();

        session()->flash('success', 'Gambar berhasil dihapus.');
    }
};

$removeUploadedPhoto = function (int $index) {
    $photos = $this->photos;
    array_splice($photos, $index, 1);
    $this->photos = $photos;
};

?>

<div>
    <div class="flex flex-col gap-6 max-w-5xl">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm flex-wrap">
            <a href="{{ route('admin.kendaraan') }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">Kendaraan</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.kendaraan.detail', $kendaraan) }}" wire:navigate class="text-slate-500 hover:text-indigo-600 transition-colors">{{ $kendaraan->nama }}</a>
            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-medium">Kelola Gambar</span>
        </nav>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition class="flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Page Header --}}
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Gambar</h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ $kendaraan->merk }} {{ $kendaraan->nama }}
                <span class="font-mono text-indigo-600">{{ $kendaraan->kode_kendaraan }}</span>
                · {{ $this->images->count() }}/5 gambar
            </p>
        </div>

        {{-- Upload Section --}}
        @if($this->images->count() < 5)
            <form wire:submit="uploadPhotos" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Upload Gambar Baru</h3>

                    {{-- Dropzone --}}
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-indigo-300 transition-colors"
                        x-data x-on:dragover.prevent="$el.classList.add('border-indigo-400', 'bg-indigo-50/50')"
                        x-on:dragleave.prevent="$el.classList.remove('border-indigo-400', 'bg-indigo-50/50')"
                        x-on:drop.prevent="$el.classList.remove('border-indigo-400', 'bg-indigo-50/50')">

                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        <p class="text-sm text-slate-500 mb-2">Drag & drop gambar di sini, atau</p>
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold cursor-pointer hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Pilih File
                            <input type="file" wire:model="photos" multiple accept="image/jpeg,image/png" class="hidden">
                        </label>
                        <p class="text-xs text-slate-400 mt-3">JPEG, PNG · Maks. 2MB per file · Maks. {{ 5 - $this->images->count() }} gambar lagi</p>
                    </div>

                    {{-- Upload Errors --}}
                    @error('photos') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror

                    {{-- Preview --}}
                    @if(count($photos) > 0)
                        <div class="mt-4">
                            <p class="text-xs text-slate-500 font-medium mb-2">{{ count($photos) }} file dipilih:</p>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                @foreach($photos as $index => $photo)
                                    <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                        <button type="button" wire:click="removeUploadedPhoto({{ $index }})"
                                            class="absolute top-1 right-1 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Loading --}}
                    <div wire:loading wire:target="photos" class="mt-4 flex items-center gap-2 text-sm text-indigo-600">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses file...
                    </div>
                </div>

                @if(count($photos) > 0)
                    <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <x-button type="submit" variant="primary">
                            <span wire:loading.remove wire:target="uploadPhotos">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload {{ count($photos) }} Gambar
                            </span>
                            <span wire:loading wire:target="uploadPhotos" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengupload...
                            </span>
                        </x-button>
                    </div>
                @endif
            </form>
        @else
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="text-sm font-medium text-amber-800">Batas maksimal 5 gambar telah tercapai. Hapus gambar lama untuk menambahkan yang baru.</span>
            </div>
        @endif

        {{-- Existing Images Grid --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Gambar Saat Ini ({{ $this->images->count() }})</h3>
            </div>

            @if($this->images->isNotEmpty())
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($this->images as $image)
                        <div class="relative group rounded-2xl overflow-hidden border-2 {{ $image->thumbnail ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200' }} transition-all">
                            {{-- Image --}}
                            <div class="aspect-square bg-slate-100">
                                <img src="{{ $image->image_url }}" alt="Gambar kendaraan" class="w-full h-full object-cover">
                            </div>

                            {{-- Thumbnail Badge --}}
                            @if($image->thumbnail)
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-sm">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Thumbnail
                                    </span>
                                </div>
                            @endif

                            {{-- Actions Overlay --}}
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                @if(!$image->thumbnail)
                                    <button wire:click="setThumbnail({{ $image->id }})"
                                        class="p-2 rounded-xl bg-white/90 text-indigo-600 hover:bg-white transition-colors" title="Set sebagai thumbnail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </button>
                                @endif
                                <button wire:click="confirmDeleteImage({{ $image->id }})"
                                    class="p-2 rounded-xl bg-white/90 text-rose-600 hover:bg-white transition-colors" title="Hapus gambar">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Belum ada gambar</h3>
                    <p class="text-sm text-slate-500">Upload gambar kendaraan menggunakan form di atas.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Image Modal --}}
    @if($deleteImageId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="cancelDeleteImage"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus Gambar?</h3>
                    <p class="text-sm text-slate-500 mb-6">Gambar akan dihapus secara permanen.</p>
                    <div class="flex items-center gap-3 w-full">
                        <x-button variant="outline" wire:click="cancelDeleteImage" class="flex-1">Batal</x-button>
                        <x-button variant="danger" wire:click="deleteImage" class="flex-1">
                            <span wire:loading.remove wire:target="deleteImage">Ya, Hapus</span>
                            <span wire:loading wire:target="deleteImage">Menghapus...</span>
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
