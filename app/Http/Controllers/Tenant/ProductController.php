<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Cache::remember('products_list', 3600, function () {
            return Product::latest()->paginate(10);
        });

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        Cache::forget('products_list');

        return redirect()->route('products.index')->with('message', 'Product created successfully');
    }

    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        Cache::forget('products_list');

        return redirect()->route('products.index')->with('message', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        Cache::forget('products_list');

        return redirect()->route('products.index')->with('message', 'Product deleted successfully');
    }
}