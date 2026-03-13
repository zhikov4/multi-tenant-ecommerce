<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Tenant/Products/Index', [
            'products' => Product::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Tenant/Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . rand(1000, 9999);

        Product::create($validated);

        return redirect()->route('tenant.products.index')->with('message', 'Produk berhasil ditambah!');
    }
}