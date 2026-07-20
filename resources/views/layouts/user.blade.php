<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Melakuy - Temukan dan pinjam kendaraan impian dengan mudah di Surabaya">
        <title>@yield('title', 'Melakuy - Sewa Kendaraan Mudah & Cepat')</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">

        <!-- CSS & JS Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])



        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>

    <body class="bg-[#eff0f3] text-slate-900 antialiased selection:bg-indigo-500 selection:text-white"
        x-data="{ mobileMenuOpen: false }">

        <!-- Navbar  -->
        <nav
            class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center gap-2">

                        <a href="{{ route('home') }}"
                            class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                            Melakuy.
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center gap-8">
                        <a wire:navigate href="{{ route('home') }}"
                            class="text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'text-[#ff8e3c]' : 'text-slate-600 hover:text-[#ff8e3c]' }}">
                            Beranda
                        </a>
                        <a wire:navigate href="{{ route('user.daftar') }}"
                            class="text-sm font-semibold transition-all duration-200 {{ request()->routeIs('user.daftar') ? 'text-[#ff8e3c]' : 'text-slate-600 hover:text-[#ff8e3c]' }}">
                            Katalog
                        </a>
                        <a wire:navigate href="{{ route('user.pencarian') }}"
                            class="text-sm font-semibold transition-all duration-200 {{ request()->routeIs('user.pencarian') ? 'text-[#ff8e3c]' : 'text-slate-600 hover:text-[#ff8e3c]' }}">
                            Cari
                        </a>
                        <a wire:navigate href="{{ route('user.history') }}"
                            class="text-sm font-semibold transition-all duration-200 {{ request()->routeIs('user.history') ? 'text-[#ff8e3c]' : 'text-slate-600 hover:text-[#ff8e3c]' }}">
                            Riwayat
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="text-slate-600 hover:text-slate-900 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                x-cloak class="md:hidden absolute top-full left-0 w-full bg-white border-b border-slate-200 shadow-xl">
                <div class="px-4 pt-2 pb-6 space-y-1 flex flex-col">
                    <a wire:navigate href="{{ route('home') }}"
                        class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-[#ff8e3c] text-white' : 'text-slate-700 hover:bg-[#ff8e3c] hover:text-white' }}">Beranda</a>
                    <a wire:navigate href="{{ route('user.daftar') }}"
                        class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('user.daftar') ? 'bg-[#ff8e3c] text-white' : 'text-slate-700 hover:bg-[#ff8e3c] hover:text-white' }}">Katalog
                        Kendaraan</a>
                    <a wire:navigate href="{{ route('user.pencarian') }}"
                        class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('user.pencarian') ? 'bg-[#ff8e3c] text-white' : 'text-slate-700 hover:bg-[#ff8e3c] hover:text-white' }}">Pencarian</a>
                    <a wire:navigate href="{{ route('user.history') }}"
                        class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('user.history') ? 'bg-[#ff8e3c] text-white' : 'text-slate-700 hover:bg-[#ff8e3c] hover:text-white' }}">Riwayat</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="w-full min-h-screen pt-20">
            {{ $slot }}
        </main>

        <!-- Footer Premium -->
        <footer class="bg-[#eff0f3] border-t border-slate-200 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-8">
                    <!-- Brand Info -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-2 mb-4">

                            <span class="text-2xl font-extrabold tracking-tight text-slate-900">Melakuy.</span>
                        </div>
                        <p class="text[#2a2a2a] text-sm leading-relaxed max-w-sm mb-6">
                            Platform penyewaan kendaraan terpercaya di Surabaya. Kami menyediakan berbagai macam pilihan
                            motor dan mobil dengan kondisi prima untuk menemani perjalanan Anda.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-green-200 hover:text-green-600 transition-colors">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor"
                                    class="w-5 h-5">
                                    <path
                                        d="M16.001 3C8.82 3 3 8.82 3 16c0 2.293.6 4.538 1.742 6.521L3 29l6.655-1.717A12.93 12.93 0 0 0 16 29c7.18 0 13-5.82 13-13S23.18 3 16.001 3zm0 23.667a10.6 10.6 0 0 1-5.397-1.481l-.386-.229-3.949 1.019 1.054-3.849-.251-.396A10.58 10.58 0 0 1 5.333 16C5.333 10.11 10.11 5.333 16 5.333S26.667 10.11 26.667 16 21.89 26.667 16.001 26.667zm5.821-7.927c-.319-.16-1.887-.932-2.179-1.038-.293-.106-.506-.16-.719.16-.213.319-.825 1.038-1.011 1.251-.186.213-.373.24-.692.08-.319-.16-1.347-.496-2.566-1.582-.949-.846-1.589-1.89-1.775-2.209-.186-.319-.02-.491.14-.651.144-.143.319-.373.479-.559.16-.186.213-.319.319-.532.106-.213.053-.399-.027-.559-.08-.16-.719-1.731-.985-2.37-.26-.625-.524-.54-.719-.549h-.612c-.213 0-.559.08-.852.399-.293.319-1.118 1.092-1.118 2.662 0 1.57 1.145 3.086 1.305 3.299.16.213 2.256 3.446 5.468 4.83.764.33 1.36.527 1.825.675.767.244 1.465.21 2.017.127.615-.092 1.887-.772 2.153-1.517.266-.745.266-1.384.186-1.517-.08-.133-.293-.213-.612-.373z" />
                                </svg>

                            </a>
                            <a href="https://www.instagram.com/melakuy/"
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#d9376e] hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Kontak -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Hubungi Kami</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm text-slate-500 leading-relaxed">
                                    Jl. Ketintang Baru, Surabaya, Jawa Timur
                                </span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="https://wa.me/6281234567890"
                                    class="text-sm text-slate-500 hover:text-indigo-600 transition-colors">
                                    +62 812-3456-7890
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="mt-12 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-slate-400">
                        &copy; {{ date('Y') }} Melakuy. All rights reserved.
                    </p>

                </div>
            </div>
        </footer>
    </body>

</html>
