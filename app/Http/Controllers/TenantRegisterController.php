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
            'stocgÿname.unique' => 'Nama toko ini sudah digunakan, silahkan coba nama lain.',
        ]);

        $tenantId = strtolower($request->store_name);
        $tenant = Tenant::create(['id' => $tenantId]);

        $centralDomains = config('tenancy.central_domains', ['localhost']);
        $domain = $tenantId . '.' . ($centralDomains[0] ?? 'localhost');

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        // Use Inertia::location for external/subdomain redirects
        return Inertia::location(http_build_url($domain));
    }
}

function http_build_url($domain) {
    return 'http://' . $domain . ':8000/register';
}
