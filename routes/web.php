<?php

use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['domain' => 'localhost'], function () {

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    })->name('central.home');

    Route::get('/tenancy-assets/{path}', function () {
        return abort(404);
    })->name('stancl.tenancy.asset');

    require __DIR__.'/auth.php';

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/my-stores/{id}', function ($id) {
            return Inertia::render('Store/Manage', ['storeId' => $id]);
        })->name('store.manage.detail');

        Route::get('/cart', function () {
            return Inertia::render('Cart');
        })->name('cart');

        Route::get('/settings', function () {
            return Inertia::render('Settings');
        })->name('settings');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('/create-store', [TenantRegisterController::class, 'store'])->name('central.store.create');

    });

});