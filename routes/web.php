<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirSimpleController;
use App\Http\Controllers\Auth\KasirAuthController;

// Route auth untuk kasir
Route::get('/kasir/login', [KasirAuthController::class, 'showLoginForm'])->name('kasir.login');
Route::post('/kasir/login', [KasirAuthController::class, 'login']);
Route::post('/kasir/logout', [KasirAuthController::class, 'logout'])->name('kasir.logout');

// Route protected untuk kasir
Route::middleware(['auth', 'kasir'])->group(function () {
    Route::match(['GET', 'POST'], '/kasir', [KasirSimpleController::class, 'index'])
        ->name('kasir.simple');

    Route::get('/kasir/cetak-struk/{id}', [KasirSimpleController::class, 'cetakStruk'])
        ->name('kasir.cetak-struk');

    Route::get('/kasir/auto-redirect', function () {
        return redirect('/kasir');
    })->name('kasir.redirect');
});

// Route default - redirect ke login kasir
Route::get('/', function () {
    return redirect('/kasir/login');
});

// Fallback route untuk handle undefined routes
Route::fallback(function () {
    return redirect('/kasir/login');
});