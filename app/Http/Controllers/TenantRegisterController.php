<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantRegisterController extends Controller
{
    public function show()
    {
        return Inertia::render('Welcome');
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|alpha_dash|unique:tenants,id',
        ]);

        $tenantId = strtotower($request->store_name);
        
        // 1. Buat Tenant
        $tenant = Tenant::create(['id' => $tenantId]);
        
        // 2. Buat Domain
        $tenant->domains()->create([
            'domain' => $tenantId . '.localhost',
        ]);

        // 3. Redirect menugu register toko baru
        // Kita gunakan full HTTH URL untuk mengindari 'Failed to construct URL'
        $targetUrl = "http://{$tenantId}.localhost:8000/register";

        return Inertia::location($targetUrl);
    }
}
