<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userTenant = Tenant::where('user_id', auth()->id())->first();
        $storeUrl = null;
        $storeName = null;
        $storeDisplay = null;

        if ($userTenant) {
            $domain = $userTenant->domains()->first();
            if ($domain) {
                $storeUrl = 'http://' . $domain->domain . ':8000/products';
                $storeName = $userTenant->id;
                $storeDisplay = $domain->domain;
            }
        }

        return Inertia::render('Dashboard', [
            'status' => [
                'hasStore'     => (bool) $userTenant,
                'storeUrl'     => $storeUrl,
                'storeName'    => $storeName,
                'storeDisplay' => $storeDisplay,
            ],
            'auth' => [
                'user' => auth()->user(),
            ],
            'discoveryProducts' => [],
        ]);
    }
}