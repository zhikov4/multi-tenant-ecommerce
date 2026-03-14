<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\WelcomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['domain' => 'localhost'], function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('central.home');
    Route::get('/tenancy-assets/{path}', fn() => abort(404))->name('stancl.tenancy.asset');

    require __DIR__ . '/auth.php';

    Route::get('/debug-config', function() {
        return response()->json([
            'template_connection' => config('tenancy.database.template_tenant_connection'),
            'driver' => config('database.connections.tenant.driver'),
            'central' => config('tenancy.database.central_connection'),
        ]);
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

        Route::get('/create-store', [TenantRegisterController::class, 'create'])->name('central.store.form');
        Route::post('/create-store', [TenantRegisterController::class, 'store'])->name('central.store.create');

        Route::get('/my-stores/{id}', function ($id) {
            return Inertia::render('Store/Manage', ['storeId' => $id]);
        })->name('store.manage.detail');

        Route::get('/settings', fn() => Inertia::render('Settings'))->name('settings');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});