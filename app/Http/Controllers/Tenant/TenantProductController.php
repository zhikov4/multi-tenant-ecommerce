<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;

class TenantProductController extends Controller
{
    public function index()
    {
        // Kita paksa Laravel untuk mengambil produk lewat koneksi 'tenant'
        $products = Product::on('tenant')->get();

        // Kita kembalikan sebagai JSON untuk testing biar gampang
        return response()->json($products);
    }
}
