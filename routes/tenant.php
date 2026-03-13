<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\ProductController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    
    Route::prefix('seller')->name('tenant.')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    });

    Route::get('/', function () {
        return redirect()->route('tenant.products.index');
    });
});

// FIX ERROR 500: Panggil rute bawaan stancl/tenancy di sini biar mesinnya nggak bingung
\Stancl\Tenancy\Controllers\TenantAssetController::class;