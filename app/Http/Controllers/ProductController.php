<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
    public function index() {
        return Inertia::render('Products/Index', [
            'products' => Product::all()
        ]);
    }
    public function store(Request $request) {
        Product::create($request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]));
        return back();
    }
    public function destroy(Product $product) {
        $product->delete();
        return back();
    }
}
