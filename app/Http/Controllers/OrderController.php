<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil pesanan milik user login + item di dalamnya + data produknya
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }
}