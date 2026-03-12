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
            'store_name.unique' => 'Nama toko ini sudah digunakan, silahkan coba nama lain.',
        ]);

        $tenantId = strtolower($request->store_name);
        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create([
            'domain' => $tenantId . '.' . config('tenansy.central_domains')[0],
        ]);

        return redirect()->away('http://' . $tenantId . '.localhost:8000/register');
    }
}
