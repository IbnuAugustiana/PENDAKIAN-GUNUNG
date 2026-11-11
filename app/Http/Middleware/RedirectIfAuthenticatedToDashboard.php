<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * Jika user sudah login, arahkan ke dashboard sesuai role.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Kalau admin, ke dashboard admin
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Kalau user biasa, ke dashboard user
            return redirect()->route('dashboard');
        }

        // Kalau belum login, lanjut ke halaman berikutnya (login/register)
        return $next($request);
    }
}
