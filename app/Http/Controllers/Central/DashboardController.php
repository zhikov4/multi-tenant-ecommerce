<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userTenant = Tenant::where('user_id', $user->id)->first();
        $storeUrl = null;
        $storeName = null;
        $storeDisplay = null;

        if ($userTenant) {
            $domain = $userTenant->domains()->first();
            if ($domain) {
                $storeUrl     = 'http://' . $domain->domain . ':8000/products';
                $storeName    = $userTenant->id;
                $storeDisplay = $domain->domain;
            }
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'status' => [
                'hasStore'     => (bool) $userTenant,
                'storeUrl'     => $storeUrl,
                'storeName'    => $storeName,
                'storeDisplay' => $storeDisplay,
            ],
            'cartItems' => $cartItems,
        ]);
    }
}