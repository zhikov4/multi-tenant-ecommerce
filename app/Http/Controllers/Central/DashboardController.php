<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user()->load('tenant.domains');

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'name'     => $item->product->name ?? 'Unknown',
                    'price'    => $item->product->price ?? 0,
                    'quantity' => $item->quantity,
                    'subtotal' => ($item->product->price ?? 0) * $item->quantity,
                ];
            });

        $tenantDomain = null;
        if ($user->tenant && $user->tenant->domains->isNotEmpty()) {
            $tenantDomain = $user->tenant->domains->first()->domain;
        }

        return Inertia::render('Dashboard', [
            'cartItems'    => $cartItems,
            'cartTotal'    => $cartItems->sum('subtotal'),
            'tenantDomain' => $tenantDomain,
            'tenant'       => $user->tenant ? [
                'id'   => $user->tenant->id,
                'name' => $user->tenant->name ?? $user->tenant->id,
            ] : null,
        ]);
    }
}