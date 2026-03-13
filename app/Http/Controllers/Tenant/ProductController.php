<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Tenant/Products/Index', [
            'products' => Product::latest()->get(),
            'tenant_id' => tenant('id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        
        Product::create($validated);

        return redirect()->back()->with('message', 'Produk berhasil ditambahkan!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('message', 'Produk berhasil dihapus.');
    }
}