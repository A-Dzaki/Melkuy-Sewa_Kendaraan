<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Memastikan user yang mengakses halaman admin
     * sudah login DAN memiliki role admin/super_admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'Akses ditolak.');
            }

            return redirect()->route('login')
                ->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
