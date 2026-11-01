<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek apakah user yang login memiliki role yang sesuai.
     * Penggunaan: route()->middleware('role:admin') atau 'role:anggota'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            // Admin mencoba akses halaman anggota → redirect ke dashboard admin
            if ($request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // Anggota mencoba akses halaman admin → abort 403
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
