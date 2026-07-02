<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Melakuy - Temukan dan pinjam motor dengan mudah di Surabaya">
        <title>@yield('title', 'Peminjaman Motor')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    <body>
        <nav
            class="bg-white/70 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    <!-- LOGO BRAND -->
                    <div class="flex-shrink-0">
                        <a href="#"
                            class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-gray-900 via-indigo-950 to-indigo-600 bg-clip-text text-transparent hover:opacity-80 transition-opacity">
                            Melakuy
                        </a>
                    </div>

                    <!-- MENU NAVIGASI (Desktop) -->
                    <!-- hidden md:flex artinya: sembunyi di HP, muncul sebagai flex di tablet/laptop -->
                    <div class="hidden md:flex items-center gap-8">
                        <a class="text-sm font-medium text-indigo-600 transition-colors duration-200"
                            aria-current="page" href="{{ route('home') }}">Home</a>
                        <a class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200"
                            href="{{ route('user.daftar') }}">Daftar Kendaraan</a>
                        <a href="{{ route('user.pencarian') }}"
                            class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200">Pencarian</a>
                        <a class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200"
                            href="{{ route('user.history') }}">Riwayat</a>
                    </div>

                    <!-- TOMBOL LOGIN (Desktop) -->
                    <div class="hidden md:flex items-center">
                        <a href="#"
                            class="inline-flex items-center justify-center px-4 h-9 text-sm font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition-colors duration-200 shadow-sm">
                            Login
                        </a>
                    </div>

                    <!-- TOMBOL MENU HP (Hamburger Button - Muncul hanya di HP) -->
                    <div class="flex md:hidden">
                        <button type="button"
                            class="text-gray-500 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-50">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </nav>
        <main class="w-full min-h-screen bg-gray-50">

            <section class="w-full aspect-video md:aspect-vidio bg-cover bg-center relative flex items-center id="Home"
                style="background-image: url('{{ asset('storage/images/photo-1469854523086-cc02fe5d8800.jpg') }}');">
                <!-- Overlay Gradasi Gelap -->
                <div class="absolute inset-0 bg-linear-to-r from-black/80 via-black/50 to-transparent"></div>

                <!-- Konten di sebelah kiri -->
                <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 w-full">
                    <div class=" text-white">
                        <span class="text-xs font-bold tracking-widest uppercase bg-indigo-600 px-3 py-1 rounded-full">
                            Melakuy,Pinjam dan Jalan
                        </span>
                        <div class="max-w-xl">
                            <h1 class="text-4xl sm:text-5xl font-black mt-4 leading-tight">
                                Pinjam Kendaraan dengan Mudah & Temukan Petualangan Barumu
                            </h1>
                            <p class="mt-4 text-base text-gray-200">
                                Rencanakan peminjaman kendaraan tanpa ribet terbaik di seluruh Indonesia.
                            </p>
                        </div>
                        <div
                            class="w-full max-w-5xl mx-auto mt-8 rounded-[28px] border border-white/60 bg-white/95 p-5 shadow-[0_25px_80px_-25px_rgba(15,23,42,0.45)] backdrop-blur sm:p-7">
                            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>

                                    <h2 class="mt-3 text-xl font-bold text-gray-900">Temukan Kendaraan Impianmu</h2>
                                    <p class="mt-1 text-sm text-gray-600">Pilih brand dan transmisi sesuai kebutuhan
                                        perjalananmu.</p>
                                </div>
                                <div
                                    class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                    <span class="block text-[10px] uppercase tracking-[0.25em]">Tersedia</span>
                                    <span class="text-base font-semibold">100+ Kendaraan</span>
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
                                        <label for="transmisi"
                                            class="text-sm font-semibold text-gray-700">Transmisi</label>
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
                    </div>
                </div>

            </section>

        </main>
        <footer></footer>
    </body>

</html>
