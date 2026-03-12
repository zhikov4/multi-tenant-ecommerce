<?php
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;

// Halaman utama Landlord (Marketplace Discovery)
Route::get('/', [TenantRegisterController::class, 'index'])->name('central.home');

Route::middleware(['auth'])->group(function () {
    Route::post('/tenants', [TenantRegisterController::class, 'store'])->name('central.tenant.store');
});

require __DIR__.'/auth.php';
