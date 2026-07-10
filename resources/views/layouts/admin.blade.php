<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Melakuy Admin Dashboard">
        <title>@yield('title', 'Admin Dashboard - Melakuy')</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">

        <!-- CSS & JS Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Alpine.js sudah di-bundle oleh Livewire 4, JANGAN load ulang via CDN --}}

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body class="h-full antialiased text-slate-900" x-data="{ sidebarOpen: false }">

        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-cloak>
        </div>

        <!-- Sidebar (Dark Theme) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl lg:shadow-none">

            <!-- Sidebar Header / Logo -->
            <div class="flex h-20 shrink-0 items-center px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">Melakuy<span
                            class="text-indigo-400">Admin</span></span>
                </div>
                <!-- Close Sidebar Mobile -->
                <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar">
                <!-- Beranda Admin -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold
    {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}
    transition-colors group">

                    <svg class="w-5 h-5 shrink-0
        {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-white' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>

                    Dashboard
                </a>

                <!-- Kelola Kendaraan -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Manajemen</p>
                </div>

                <a href="{{ route('admin.kendaraan') }}" wire:navigate
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('admin.kendaraan*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.kendaraan*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-white' }} transition-colors"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Kelola Kendaraan
                </a>

                <a href="{{ route('admin.pemesanan') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold
    {{ request()->routeIs('admin.pemesanan*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}
    transition-colors group">

                    <svg class="w-5 h-5 shrink-0
        {{ request()->routeIs('admin.pemesanan*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-white' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>

                    Pemesanan
                </a>


            </nav>

            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800/50">
                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center shrink-0">
                        <span class="text-sm font-bold text-white">AD</span>
                    </div>
                    <div class="flex flex-col truncate">
                        <span class="text-sm font-semibold text-white truncate">Admin Melakuy</span>
                        <span class="text-xs text-slate-400 truncate">admin@melakuy.com</span>
                    </div>
                </div>
                <a href="#"
                    class="mt-2 flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="lg:pl-72 flex flex-col min-h-screen">

            <!-- Topbar -->
            <header
                class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/80 backdrop-blur-md px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" type="button"
                        class="lg:hidden text-slate-500 hover:text-slate-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-900 hidden sm:block">@yield('title_header', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button type="button"
                        class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors rounded-full hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>

                    <a href="{{ route('home') }}"
                        class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        Lihat Website
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>

        <!-- Custom Scrollbar Style for Sidebar -->
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #334155;
                border-radius: 4px;
            }

            .custom-scrollbar:hover::-webkit-scrollbar-thumb {
                background: #475569;
            }
        </style>
    </body>

</html>
