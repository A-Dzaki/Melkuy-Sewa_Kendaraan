<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember-me');

        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is admin
            if (! auth()->user()->isAdmin()) {
                \Illuminate\Support\Facades\Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses administrator.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }
}
