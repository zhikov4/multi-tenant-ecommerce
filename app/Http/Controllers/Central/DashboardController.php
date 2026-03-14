<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user       = $request->user();
        $userStores = [];
        $tenantDomain = null;

        $tenants = Tenant::where('user_id', $user->id)->with('domains')->get();

        foreach ($tenants as $tenant) {
            if ($tenant->domains->isNotEmpty()) {
                $domain = $tenant->domains->first()->domain;
                $scheme = $request->getScheme();
                $port   = $request->getPort();
                $portStr = ($port != '80' && $port != '443') ? ':' . $port : '';

                $userStores[] = [
                    'id'   => $tenant->id,
                    'name' => $tenant->getInternal('store_name') ?? $tenant->id,
                    'url'  => $scheme . '://' . $domain . $portStr . '/products',
                ];

                if (is_null($tenantDomain)) {
                    $tenantDomain = $domain;
                }
            }
        }

        return Inertia::render('Dashboard', [
            'userStores'   => $userStores,
            'cartItems'    => [],
            'cartTotal'    => 0,
            'tenantDomain' => $tenantDomain,
            'tenant'       => $tenants->first() ? [
                'id'   => $tenants->first()->id,
                'name' => $tenants->first()->getInternal('store_name') ?? $tenants->first()->id,
            ] : null,
        ]);
    }
}