<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class TenantProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Kamera Vintage Retro', 'price' => 2500000, 'description' => 'Kamera klasik untuk hasil foto estetik.', 'stock' => 10],
            ['name' => 'Sepatu Sneakers Putih', 'price' => 850000, 'description' => 'Nyaman dipakai dan stylish.', 'stock' => 25],
            ['name' => 'Tas Kulit Handmade', 'price' => 1200000, 'description' => 'Dibuat dari kulit asli berkualitas tinggi.', 'stock' => 5],
            ['name' => 'Kacamata Anti Radiasi', 'price' => 350000, 'description' => 'Melindungi mata dari layar gadget.', 'stock' => 50],
            ['name' => 'Jam Tangan Minimalis', 'price' => 1500000, 'description' => 'Desain elegan untuk acara formal.', 'stock' => 12],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
