<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $allProducts = collect();

        foreach (Tenant::all() as $tenant) {
            try {
                tenancy()->initialize($tenant);

                $products = Product::where('is_active', true)
                    ->where('stock', '>', 0)
                    ->latest()
                    ->take(6)
                    ->get()
                    ->map(fn($p) => [
                        'id'          => $p->id,
                        'tenant_id'   => $tenant->id,
                        'name'        => $p->name,
                        'price'       => (float) $p->price,
                        'description' => $p->description,
                        'stock'       => $p->stock,
                        'category'    => $p->category,
                        'store'       => strtoupper($tenant->id),
                    ]);

                $allProducts = $allProducts->merge($products);
                tenancy()->end();
            } catch (\Exception $e) {
                tenancy()->end();
            }
        }

        return Inertia::render('Welcome', [
            'products'    => $allProducts->take(20)->values(),
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}