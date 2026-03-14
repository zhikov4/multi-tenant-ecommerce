<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $allProducts = collect();
        $templateConnection = config('tenancy.database.template_tenant_connection');
        $prefix = config('tenancy.database.prefix');
        $suffix = config('tenancy.database.suffix');
        $baseConfig = config('database.connections.' . $templateConnection);

        foreach (Tenant::all() as $tenant) {
            try {
                $dbName = $prefix . $tenant->getTenantKey() . $suffix;
                $connectionName = 'tenant_' . $tenant->getTenantKey();

                config(['database.connections.' . $connectionName => array_merge($baseConfig, [
                    'database' => $dbName,
                ])]);

                $products = DB::connection($connectionName)
                    ->table('products')
                    ->where('is_active', true)
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

                DB::purge($connectionName);
            } catch (\Throwable $e) {
                DB::purge('tenant_' . $tenant->getTenantKey());
            }
        }

        return Inertia::render('Welcome', [
            'products'    => $allProducts->take(20)->values(),
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}