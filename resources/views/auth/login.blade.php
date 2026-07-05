<!-- halaman login untuk admin saja ya sob jangan di ganti ganti -->
<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Administrator - Melakuy</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body
        class="bg-slate-50 antialiased selection:bg-indigo-500 selection:text-white flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8 relative">

        <!-- Tombol Kembali ke Beranda Sbg Guest -->
        <div class="absolute top-8 left-8">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda (Guest)
            </a>
        </div>

        <!-- Kontainer Utama Portal Admin (Menggunakan Alpine untuk manajemen global loading) -->
        <div class="sm:mx-auto w-full max-w-md px-4 sm:px-0" x-data="{ isSubmitting: false, isGoogleLoading: false }">
            <!-- Logo & Judul -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-900/20 mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-950">Melakuy Admin Control</h2>
                <p class="mt-1.5 text-sm text-slate-500">
                    Gunakan kredensial resmi untuk mengakses dashboard manajemen.
                </p>
            </div>

            <!-- Box Form Login -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200/60 shadow-xl shadow-slate-200/30">
                <!-- Form Konten -->
                <form action="{{ route('login.post') }}" method="POST" @submit="isSubmitting = true" class="space-y-5">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Email
                            Administrator</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            :disabled="isGoogleLoading" value="{{ old('email') }}"
                            class="block w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-slate-900 text-sm focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5 transition-all outline-none disabled:opacity-50 @error('email') border-rose-500 @enderror"
                            placeholder="nama@melakuy.com">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password dengan Scope Alpine Mandiri -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600">Password</label>
                            <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Lupa
                                sandi?</a>
                        </div>

                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password" required :disabled="isGoogleLoading"
                                class="block w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-12 text-slate-900 text-sm focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5 transition-all outline-none disabled:opacity-50"
                                placeholder="••••••••">

                            <!-- Tombol Toggle Mata -->
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4M9.9 4.24a9.122 9.122 0 012.1-.24c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.4M9.9 4.24L12 6.34m0 0l2.1 2.1M12 6.34l-2.1 2.1m2.1-2.1l2.1-2.1M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" :disabled="isGoogleLoading"
                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900 accent-slate-900 cursor-pointer disabled:opacity-50">
                        <label for="remember-me"
                            class="ml-2 block text-sm text-slate-600 select-none cursor-pointer">Ingat perangkat
                            ini</label>
                    </div>

                    <!-- Tombol Submit Utama -->
                    <div class="pt-1">
                        <button type="submit" :disabled="isSubmitting || isGoogleLoading"
                            class="relative flex w-full h-11 items-center justify-center rounded-xl bg-slate-950 text-sm font-semibold text-white hover:bg-slate-900 active:scale-[0.98] transition-all shadow-md shadow-slate-950/10 disabled:opacity-75 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Otorisasi & Masuk</span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses Otorisasi...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Garis Pembatas (Divider) -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs font-medium uppercase tracking-wider">
                        <span class="bg-white px-3 text-slate-400">Atau masuk melalui</span>
                    </div>
                </div>

                <!-- Tombol Login Google -->
                <div>
                    <!-- Ubah href sesuai route Laravel Socialite kamu (misal: route('login.google')) -->
                    <a href="{{ route('login.google') }}" @click="isGoogleLoading = true; isSubmitting = false"
                        :class="isSubmitting || isGoogleLoading ? 'opacity-60 pointer-events-none' : ''"
                        class="inline-flex w-full h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 active:bg-slate-100 transition-all duration-200">

                        <!-- Kondisi Normal: Tampilkan SVG Icon Google -->
                        <span x-show="!isGoogleLoading" class="flex items-center justify-center gap-2.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4" />
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853" />
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                                    fill="#FBBC05" />
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z"
                                    fill="#EA4335" />
                            </svg>
                            Masuk dengan Google
                        </span>

                        <!-- Kondisi Loading: Tampilkan Spinner -->
                        <span x-show="isGoogleLoading" x-cloak class="flex items-center gap-2 text-slate-500">
                            <svg class="animate-spin h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menghubungkan ke Google...
                        </span>
                    </a>
                </div>
            </div>

            <!-- Footer Kecil Internal -->
            <p class="mt-8 text-center text-xs text-slate-400">
                Sistem Internal Melakuy &copy; 2026. Hak Cipta Dilindungi.
            </p>
        </div>

    </body>

</html>
