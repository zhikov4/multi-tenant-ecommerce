<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;

class TenantProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::latest()->get());
    }
}