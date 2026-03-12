<?php
use App\Http\Controllers\TenantRegisterController;
use Illuminate\Support\Facades\Route;

// Halaman utama Landlord untuk Discovery Toko
Route::get('/', [TenantRegisterController::class, 'index'])->name('central.home');

Route::middleware(['auth'])->group(function () {
    // Seller membuat toko baru (Satu akun bisa banyak toko)
    Route::post('/tenants', [TenantRegisterController::class, 'store'])->name('central.tenant.store');
});

require __DIR__.'/auth.php';
