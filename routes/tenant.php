<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\ProductController;
use Inertia\Inertia;

Route::middleware([
    'web',
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
])->group(function () {

    // 1. Halaman Depan Toko (Storefront)
    Route::get('/', function () {
        return "<h1>Selamat Datang di Toko " . tenant('id') . "</h1><p>Akses <a href='/console'>/console</a> untuk manage produk.</p>";
    });

    // 2. Halaman Management Console (Halaman Vue Manage.vue)
    Route::get('/console', function () {
        return Inertia::render('Store/Manage');
    })->name('tenant.console');

    // 3. API untuk CRUD Produk
    Route::prefix('api')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
});