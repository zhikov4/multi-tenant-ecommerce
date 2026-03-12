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
        ], [
            'stock' => 'Nama toko ini sudah digunakan, silahkan coba nama lain.',
        ]);

        $tenantId = strtolower($request->store_name);
        $tenant = Tenant::create(['id' => $tenantId]);

        // Mengambil central domain dengan fallback ke localhost
        $centralDomains = config('tenancy.central_domains', ['localhost']);
        $domain = $tenantId . '.' . ($centralDomains[0] ?? 'localhost');

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        return redirect()->away('http://' . $domain . ':8000/register');
    }
}
