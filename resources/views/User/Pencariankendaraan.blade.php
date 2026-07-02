<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Kendaraan - Melakuy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="/" class="text-2xl font-extrabold tracking-tight text-slate-900">Melakuy</a>
        <div class="hidden items-center gap-6 md:flex">
            <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Home</a>
            <a href="{{ route('user.daftar') }}" class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Daftar Kendaraan</a>
           
            <a href="{{ route('user.history') }}" class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Riwayat</a>
        </div>
        <a href="#" class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
    </div>
</nav>

<main class="min-h-screen bg-slate-50">
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-700">Pencarian Kendaraan</span>
                    <h1 class="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">Cari motor sesuai kebutuhanmu</h1>
                    <p class="mt-3 text-base text-slate-600">Filter berdasarkan brand, transmisi, dan jadwal peminjaman.</p>
                </div>
                <div class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-medium text-white">
                    <p>Hasil terbaru</p>
                    <p class="mt-1 text-lg font-semibold">3 kendaraan cocok</p>
                </div>
            </div>

            <form class="mt-8 grid gap-4 lg:grid-cols-[1.2fr_1fr_auto] lg:items-end">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Brand</label>
                    <select class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option>Semua Brand</option>
                        <option>Honda</option>
                        <option>Yamaha</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Transmisi</label>
                    <select class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option>Semua Transmisi</option>
                        <option>Matic</option>
                        <option>Manual</option>
                    </select>
                </div>
                <button type="submit" class="h-12 rounded-2xl bg-emerald-500 px-5 font-semibold text-white transition hover:bg-emerald-600">Cari Sekarang</button>
            </form>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900">Honda Scoopy</h2>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">Cocok</span>
                </div>
                <p class="mt-3 text-sm text-slate-600">Matic • 110cc • Tersedia 2 unit</p>
                <div class="mt-5 flex items-center justify-between text-sm text-slate-600">
                    <span>Rp 90.000/hari</span>
                    <a href="#" class="font-semibold text-indigo-600">Pesan sekarang</a>
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900">Yamaha Mio</h2>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">Cocok</span>
                </div>
                <p class="mt-3 text-sm text-slate-600">Matic • 125cc • Tersedia 1 unit</p>
                <div class="mt-5 flex items-center justify-between text-sm text-slate-600">
                    <span>Rp 100.000/hari</span>
                    <a href="#" class="font-semibold text-indigo-600">Pesan sekarang</a>
                </div>
            </article>
        </div>
    </section>
</main>
</body>
</html>
