<?php
declare(strict_types=1);
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('products.index');
    })->name('tenant.home');

    Route::middleware('auth')->group(function () {
        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'store' => 'products.store',
            'destroy' => 'products.destroy',
        ]);
    });
    require __DIR__.'/auth.php';
});
