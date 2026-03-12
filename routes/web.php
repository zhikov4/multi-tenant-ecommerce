<?php
use App\Http\Controllers\TenantRegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [TenantRegisterController::class, 'index'])->name('central.home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/tenants', [TenantRegisterController::class, 'store'])->name('central.tenant.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

require __DIR__.'/auth.php';
