<?php
declare(strict_types=1);
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Product;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // 1. Publik Storefront (Untuk Buyer)
    Route::get('/', function () {
        return Inertia::render('Shop/Catalog', [
            'products' => Product::all(),
            'tenantId' => tenant('id'),
            'auth' => ['user' => auth()->user()] // Akun Buyer dari DB Tenant
        ]);
    })->name('tenant.catalog');

    // 2. Admin Area (Untuk Seller mengelola toko ini)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () { return redirect()->route('products.index'); })->name('dashboard');
        Route::resource('products', ProductController::class);
    });

    require __DIR__.'/auth.php'; // Auth di sini akan mengakses DB Tenant (untuk Buyer)
});
