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
    // Katalog Publik untuk Pembeli
    Route::get('/', function () {
        return Inertia::render('Shop/Catalog', [
            'products' => Product::all(),
            'tenantId' => tenant('id'),
            'auth' => ['user' => auth()->user()]
        ]);
    })->name('tenant.catalog');

    // Panel khusus Seller/Admin Toko
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () { return redirect()->route('products.index'); })->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::get('/sales-report', function () { return Inertia::render('Sales/Report'); })->name('sales.report');
    });

    require __DIR__.'/auth.php';
});
