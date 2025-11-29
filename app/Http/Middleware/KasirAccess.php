<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KasirAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isKasir()) {
            return $next($request);
        }
        
        // Jika bukan kasir, redirect ke admin kasir
        return redirect('/admin/kasir')->with('error', 'Akses hanya untuk kasir.');
    }
}