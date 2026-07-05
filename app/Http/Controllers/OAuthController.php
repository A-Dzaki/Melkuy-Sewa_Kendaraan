<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class OAuthController extends Controller
{
    /**
     * Mengalihkan pengguna ke halaman otentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani data kembalian dari Google setelah otentikasi.
     */
    public function handleGoogleCallback()
    {
        try {
            // Mengambil data user dari Google secara aman
            $googleUser = Socialite::driver('google')->user();

            // 1. Cari pengguna di database berdasarkan email Google-nya
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Tambahan Proteksi: Pastikan user tersebut memang memiliki hak akses ADMIN
                if (! $user->isAdmin()) {
                    return redirect()->route('login')
                        ->with('error', 'Akses ditolak. Akun Google Anda bukan akun administrator.');
                }

                // Update google_id dan avatar jika belum tersimpan
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                // 2. Jika dia admin dan terdaftar, langsung login-kan
                Auth::login($user, true); // true untuk 'remember me'

                // Regenerasi session untuk keamanan tambahan
                request()->session()->regenerate();

                // Alihkan ke dashboard admin
                return redirect()->intended(route('admin.dashboard'));
            } else {
                // Jika email tidak terdaftar di database kita
                return redirect()->route('login')
                    ->with('error', 'Email Google Anda tidak terdaftar dalam sistem administrator.');
            }

        } catch (Exception $e) {
            // Menangani error jika koneksi gagal atau dibatalkan oleh user
            return redirect()->route('login')
                ->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi.');
        }
    }
}