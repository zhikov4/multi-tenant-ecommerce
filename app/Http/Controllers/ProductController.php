<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
    public function index() { return Inertia::render('Products/Index', ['products' => Product::all()]); }
    public function store(Request ) {
         = Product::create((['name'=>'required', 'price'=>'required', 'stock'=>'required']));
        return back();
    }
    public function destroy(Product ) { (); return back(); }
}