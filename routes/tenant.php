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
    // Rute toko (tenant)
    Route::get('/', [ProductController::class, 'index'])->name('tenant.products.index');
});