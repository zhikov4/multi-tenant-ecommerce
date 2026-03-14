<?php
declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\ProductController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () { return redirect('/products'); });
    Route::resource('products', ProductController::class);
    Route::get('/dashboard', function () { return Inertia::render('Dashboard'); })->name('dashboard');
    Route::get('/profile', function () { return Inertia::render('Profile'); })->name('profile');
    Route::get('/settings', function () { return Inertia::render('Settings'); })->name('settings');

    // Logout Path Relatif (Requirements: User Auth)
    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/products');
    })->name('logout');
});
