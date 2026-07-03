<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Kendaraan - Melakuy</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-slate-50 text-slate-900">
        <nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="/" class="text-2xl font-extrabold tracking-tight text-slate-900">Melakuy</a>
                <div class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Home</a>
                    <a href="{{ route('user.daftar') }}" class="text-sm font-medium text-indigo-600">Daftar
                        Kendaraan</a>

                    <a href="{{ route('user.history') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Riwayat</a>
                </div>
                <a href="#"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
            </div>
        </nav>

        <main
            class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.15),_transparent_30%),linear-gradient(135deg,_#f8fafc_0%,_#eef2ff_100%)]">
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <span span
                            class="inline-flex items-center rounded-full bg-indigo-300 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">Daftar
                            Kendaraan
                        </span>
                        <h1 class="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">Pilih kendaraan yang paling
                            cocok untuk perjalananmu</h1>
                        <p class="mt-3 text-base text-slate-600">Tersedia beragam motor & mobil terawat dengan harga
                            fleksibel dan proses peminjaman yang cepat.</p>
                    </div>

                </div>

                {{-- <div class="mt-12">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($kendaraans as $kendaraan)
                            <a href="{{ route('user.detail', $kendaraan->id) }}"
                                class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
                                <div class="relative aspect-[3/2] w-full">
                                    <img src="{{ asset('storage/' . $kendaraan->image_path) }}"
                                        alt="{{ $kendaraan->name }}"
                                        class="absolute inset-0 h-full w-full object-cover transition group-hover:scale-105">
                                </div>
                                <div class="flex flex-1 flex-col gap-4 p-4">
                                    <h2 class="text-lg font-semibold text-slate-900">{{ $kendaraan->name }}</h2>
                                    <p class="text-sm text-slate-600">{{ $kendaraan->description }}</p>
                                    <span
                                        class="mt-auto text-sm font-semibold text-indigo-600">Rp{{ number_format($kendaraan->price, 0, ',', '.') }}/hari</span>
                                </div>
                            </a>
                        @endforeach
                    </div> --}}
            </section>
        </main>
    </body>

</html>
