<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirAuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke kasir
        if (auth()->check() && method_exists(auth()->user(), 'isKasir') && auth()->user()->isKasir()) {
            return redirect()->route('kasir.simple');
        }
        
        return view('auth.kasir-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek jika user adalah kasir
            if (method_exists(auth()->user(), 'isKasir') && auth()->user()->isKasir()) {
                return redirect()->intended('/kasir');
            }

            // Jika bukan kasir, logout dan redirect kembali
            Auth::logout();
            return back()->withErrors([
                'email' => 'Hanya kasir yang dapat mengakses halaman ini.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/kasir/login');
    }
}