<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user login ini punya toko
        $userTenant = Tenant::where('user_id', auth()->id())->first();

        $storeUrl = null;
        if ($userTenant) {
            $domain = $userTenant->domains()->first();
            if ($domain) {
                $storeUrl = "http://" . $domain->domain . ":8000";
            }
        }

        return Inertia::render('Dashboard', [
            'status' => [
                'hasStore' => !!$userTenant,
                'storeUrl' => $storeUrl,
                'storeName' => $userTenant->id ?? null,
            ],
            'auth' => [
                'user' => auth()->user(),
            ],
            'discoveryProducts' => [] 
        ]);
    }
}