<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kendaraan - Melakuy</title>
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
            <a href="{{ route('user.daftar') }}" class="text-sm font-medium text-indigo-600">Daftar Kendaraan</a>
          
            <a href="{{ route('user.history') }}" class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Riwayat</a>
        </div>
        <a href="#" class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
    </div>
</nav>

<main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.15),_transparent_30%),linear-gradient(135deg,_#f8fafc_0%,_#eef2ff_100%)]">
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-700">Daftar Kendaraan</span>
                <h1 class="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">Pilih motor yang paling cocok untuk perjalananmu</h1>
                <p class="mt-3 text-base text-slate-600">Tersedia beragam motor terawat dengan harga fleksibel dan proses peminjaman yang cepat.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm backdrop-blur">
                <p class="text-sm font-medium text-slate-500">Jumlah kendaraan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">24 unit</p>
                <p class="mt-1 text-sm text-emerald-600">Tersedia hari ini</p>
            </div>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="h-40 bg-gradient-to-br from-slate-800 via-slate-700 to-indigo-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Honda Beat</h2>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Tersedia</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Matic • 110cc • 1 hari</p>
                    <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                        <span>Rp 85.000/hari</span>
                        <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-700">Lihat Detail</a>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="h-40 bg-gradient-to-br from-emerald-600 via-teal-500 to-cyan-400"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Yamaha NMAX</h2>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Tersedia</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Matic • 155cc • 1 hari</p>
                    <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                        <span>Rp 125.000/hari</span>
                        <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-700">Lihat Detail</a>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="h-40 bg-gradient-to-br from-amber-500 via-orange-400 to-rose-400"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Honda Supra</h2>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Sedang dipinjam</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Manual • 125cc • 1 hari</p>
                    <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                        <span>Rp 70.000/hari</span>
                        <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-700">Lihat Detail</a>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>
</body>
</html>
