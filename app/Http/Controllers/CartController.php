<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id'  => 'required|string|exists:tenants,id',
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $tenant = Tenant::findOrFail($request->tenant_id);

        tenancy()->initialize($tenant);
        $product = Product::findOrFail($request->product_id);
        tenancy()->end();

        $existing = Cart::where('user_id', Auth::id())
            ->where('tenant_id', $request->tenant_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id'       => Auth::id(),
                'tenant_id'     => $request->tenant_id,
                'product_id'    => $request->product_id,
                'product_name'  => $product->name,
                'product_store' => strtoupper($tenant->id),
                'product_price' => $product->price,
                'quantity'      => $request->quantity,
            ]);
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return back();
    }

    public function destroy(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        $cart->delete();
        return back();
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return back();
    }
}