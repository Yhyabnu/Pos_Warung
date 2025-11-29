<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKasir
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('kasir.login');
        }

        // Cek jika user adalah kasir
        if (!method_exists(auth()->user(), 'isKasir') || !auth()->user()->isKasir()) {
            abort(403, 'Akses ditolak. Hanya untuk kasir.');
        }

        return $next($request);
    }
}