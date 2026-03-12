<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => \DB::raw("quantity + {$request->quantity}")]
        );

        return redirect()->route('cart.index');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id === Auth::id()) {
            $cart->delete();
        }
        return redirect()->back();
    }
}