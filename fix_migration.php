<?php
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

$id = 'testnewhits';
echo "--- 🏗️ Menghubungkan ke Toko: $id ---\n";

$tenant = Tenant::find($id);

if ($tenant) {
    // Pindah koneksi ke database tenant
    tenancy()->initialize($tenant);
    
    echo "--- 🔑 Memaksa Migrasi Fresh di Folder Tenant ---\n";
    
    Artisan::call('migrate:fresh', [
        '--path' => 'database/migrations/tenant',
        '--force' => true
    ]);
    
    echo Artisan::output();
    echo "--- ✅ SELESAI! Silakan cek Sequel Ace kamu! ---\n";
} else {
    echo "❌ Waduh, Tenant dengan ID '$id' tidak ditemukan di database pusat!\n";
}