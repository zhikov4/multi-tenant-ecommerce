<?php

use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Central Web Routes
|--------------------------------------------------------------------------
*/

// PAKSA rute utama muncul di localhost
Route::group(['domain' => 'localhost'], function () {
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    })->name('central.home');
});

// Fallback untuk IP atau domain central lainnya
Route::get('/', function () {
    return Inertia::render('Welcome');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/create-store', [TenantRegisterController::class, 'store'])->name('central.store.create');
});

// Emergency Asset Bypass
Route::get('/tenancy-assets/{path?}', function () { return response(''); })->name('stancl.tenancy.asset');