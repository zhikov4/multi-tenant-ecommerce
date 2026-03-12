<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantRegisterController;

foreach (config('tenancy.central_domains', ['localhost', '127.0.0.1']) as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', [TenantRegisterController::class, 'show'])->name('central.home');
        Route::post('/onboarding', [TenantRegisterController::class, 'store'])->name('central.tenant.store');
    });
}