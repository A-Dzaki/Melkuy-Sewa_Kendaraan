<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Riwayat Peminjaman - Melakuy</title>
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
                    <a href="{{ route('user.daftar') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Daftar Kendaraan</a>

                    <a href="{{ route('user.history') }}" class="text-sm font-medium text-indigo-600">Riwayat</a>
                </div>
                <a href="#"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
            </div>
        </nav>

        <main class="min-h-screen bg-slate-50">
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Riwayat Peminjaman</h1>
                    <p class="mt-2 text-sm text-slate-600">Cari riwayat peminjaman kendaraan Anda berdasarkan no
                        telepon.</p>
                </div>
                <div class="mt-8">
                    <form action="{{ route('user.history') }}" method="GET" class="flex gap-2">
                        <input type="tel" name="phone" placeholder="Masukkan No Telepon"
                            value="{{ request('phone') }}" required
                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">Cari</button>
                    </form>
                </div>
            </section>
        </main>
    </body>

</html>
