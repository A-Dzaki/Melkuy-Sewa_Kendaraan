<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Melakuy</title>
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
           
            <a href="{{ route('user.history') }}" class="text-sm font-medium text-indigo-600">Riwayat</a>
        </div>
        <a href="#" class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
    </div>
</nav>

<main class="min-h-screen bg-slate-50">
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-700">Riwayat Peminjaman</span>
                <h1 class="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">Pantau semua peminjamanmu</h1>
                <p class="mt-3 text-base text-slate-600">Lihat status, tanggal, dan detail kendaraan yang pernah kamu pinjam.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total peminjaman</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">5 kali</p>
            </div>
        </div>

        <div class="mt-10 space-y-4">
            <article class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600">#INV-1024</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Honda Beat</h2>
                    <p class="mt-1 text-sm text-slate-600">12 Juni 2026 • 2 hari • Rp 170.000</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">Selesai</span>
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-indigo-600">Lihat Detail</a>
                </div>
            </article>

            <article class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600">#INV-1031</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Yamaha NMAX</h2>
                    <p class="mt-1 text-sm text-slate-600">20 Juni 2026 • 1 hari • Rp 125.000</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">Diproses</span>
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-indigo-600">Lihat Detail</a>
                </div>
            </article>
        </div>
    </section>
</main>
</body>
</html>
