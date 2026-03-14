<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cart->sum(fn($item) => $item->product->price * $item->quantity);

        return Inertia::render('Tenant/Cart/Index', [
            'cartItems' => $cart,
            'total'     => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validated);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}