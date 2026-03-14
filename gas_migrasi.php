<?php
use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

$id = 'tokocantik';
$tenant = Tenant::find($id);

if (!$tenant) {
    exit("❌ Error: Tenant '$id' tidak ditemukan!\n");
}

echo "--- 🏗️ Menghubungkan ke: " . $tenant->id . " ---\n";

// LANGKAH SUPER PENTING: Set nama database secara manual sebelum inisialisasi
$dbName = "tenant" . $tenant->id;
Config::set('database.connections.tenant.database', $dbName);
DB::purge('tenant'); // Bersihkan koneksi lama biar nggak nyangkut

// Inisialisasi Tenancy
tenancy()->initialize($tenant);

echo "--- 🕵️‍♀️ Konfirmasi Database Aktif: " . DB::connection()->getDatabaseName() . " ---\n";

echo "--- 🛒 Mengisi Barang ke Rak Toko... ---\n";

try {
    $products = [
        ['name' => 'Kamera Vintage Retro', 'price' => 2500000, 'description' => 'Kamera klasik untuk hasil foto estetik.', 'stock' => 10],
        ['name' => 'Sepatu Sneakers Putih', 'price' => 850000, 'description' => 'Nyaman dipakai dan stylish.', 'stock' => 25],
        ['name' => 'Tas Kulit Handmade', 'price' => 1200000, 'description' => 'Dibuat dari kulit asli berkualitas tinggi.', 'stock' => 5],
        ['name' => 'Kacamata Anti Radiasi', 'price' => 350000, 'description' => 'Melindungi mata dari layar gadget.', 'stock' => 50],
        ['name' => 'Jam Tangan Minimalis', 'price' => 1500000, 'description' => 'Desain elegan untuk acara formal.', 'stock' => 12],
    ];

    foreach ($products as $p) {
        // Kita pakai DB::table biar bener-bener nembak ke koneksi tenant tanpa drama model
        DB::connection('tenant')->table('products')->insert(array_merge($p, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    echo "--- ✅ BERHASIL! Rak toko sudah penuh barang! ---\n";
} catch (\Exception $e) {
    echo "--- ❌ GAGAL: " . $e->getMessage() . "\n";
}

$count = DB::connection('tenant')->table('products')->count();
echo "--- 🕵️‍♀️ Cek jumlah produk di tenant: " . $count . " items ---\n";