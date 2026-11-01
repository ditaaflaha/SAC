<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Paksa anggota baru ganti password saat login pertama.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // Kecuali mereka sedang di route ganti-password atau logout
            if (! $request->routeIs('password.change*', 'logout')) {
                return redirect()->route('password.change')
                    ->with('info', 'Halo! Tolong ganti password default kamu dulu ya sebelum lanjut. 🏹');
            }
        }

        return $next($request);
    }
}
