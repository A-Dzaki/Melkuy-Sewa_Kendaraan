<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pencarian Kendaraan - Melakuy</title>
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

                    <a href="{{ route('user.history') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600">Riwayat</a>
                </div>
                <a href="#"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">Login</a>
            </div>
        </nav>

        <main class="min-h-screen bg-slate-50">
            <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div
                    class="w-full max-w-5xl mx-auto mt-8 rounded-[28px] border border-white/60 bg-white/95 p-5 shadow-[0_25px_80px_-25px_rgba(15,23,42,0.45)] backdrop-blur sm:p-7">
                    <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>

                            <h2 class="mt-3 text-xl font-bold text-gray-900">Temukan Kendaraan Impianmu</h2>
                            <p class="mt-1 text-sm text-gray-600">Pilih brand dan transmisi sesuai kebutuhan
                                perjalananmu.</p>
                        </div>

                    </div>

                    <form action="#" method="GET">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-[1.2fr_1fr_auto] md:items-end">
                            <!-- Untuk Menampilkan Pilihan Brand yang ada di database -->
                            <div class="flex flex-col gap-2">
                                <label for="brand" class="text-sm font-semibold text-gray-700">Brand</label>
                                <select id="brand" name="brand"
                                    class="h-12 w-full rounded-2xl border border-gray-200 bg-gray-50">
                                    <option value="">Semua Brand</option>
                                    <!-- Tambahkan opsi brand dari database di sini -->
                                </select>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="transmisi" class="text-sm font-semibold text-gray-700">Transmisi</label>
                                <select id="transmisi" name="transmisi"
                                    class="h-12 w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 text-gray-700 transition-all duration-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Semua Transmisi</option>
                                    <option value="manual">Manual</option>
                                    <option value="matic">Matic</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="jenis-kendaraan" class="text-sm font-semibold text-gray-700">Jenis
                                    Kendaraan</label>
                                <select id="jenis-kendaraan" name="jenis-kendaraan"
                                    class="h-12 w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 text-gray-700 transition-all duration-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Pilih Jenis Kendaraan</option>
                                    <option value="mobil">Mobil</option>
                                    <option value="motor">Motor</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 font-bold text-white shadow-md transition-all duration-200 hover:bg-emerald-600 hover:shadow-lg active:scale-[0.98]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                                </svg>
                                Cari Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </body>

</html>
