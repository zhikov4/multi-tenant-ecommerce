<?php

use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes (Central Domain)
|--------------------------------------------------------------------------
*/

// 1. Discovery Page (Bisa dibuka siapa saja)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('central.home');

// 2. Rute Autentikasi (Breeze)
require __DIR__.'/auth.php';

// 3. Rute Khusus User yang sudah Login
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard dengan logika pengecekan toko
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Proses pembuatan toko baru
    Route::post('/create-store', [TenantRegisterController::class, 'store'])->name('central.store.create');
});

// Bypass Asset Tenancy (Tetap simpan agar aset aman)
Route::get('/tenancy-assets/{path?}', function () {
    return response('');
})->name('stancl.tenancy.asset');