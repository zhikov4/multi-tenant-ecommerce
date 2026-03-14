<?php
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$id = 'tokocantik'; 
$tenant = Tenant::find($id);

if (!$tenant) {
    exit("❌ Error: Tenant '$id' tidak ditemukan di database pusat!\n");
}

echo "--- 🏗️ Menghubungkan ke: " . $tenant->id . " ---\n";
tenancy()->initialize($tenant);

echo "--- 🕵️‍♀️ Database Aktif: " . DB::connection()->getDatabaseName() . " ---\n";

echo "--- 🧹 Membersihkan Database Tenant ---\n";
Artisan::call('db:wipe', ['--force' => true]);

echo "--- 🚀 Menjalankan Migrasi Fresh ke Folder Tenant ---\n";
Artisan::call('migrate', [
    '--path' => 'database/migrations/tenant',
    '--force' => true
]);

echo Artisan::output();
echo "\n--- ✅ SELESAI! Silakan Refresh Sequel Ace kamu! ---\n";