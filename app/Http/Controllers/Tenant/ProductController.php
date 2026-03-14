<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Tenant/Products/Index', [
            'products' => Product::latest()->paginate(12),
            'stats'    => [
                'total_products'  => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'total_stock'     => Product::sum('stock'),
                'total_value'     => number_format(Product::where('is_active', true)->sum(DB::raw('price * stock')), 2),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Tenant/Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku',
            'is_active'   => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Tenant/Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'is_active'   => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}