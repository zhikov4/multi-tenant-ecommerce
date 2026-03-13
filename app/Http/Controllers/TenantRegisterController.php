<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantRegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validation: Pastikan subdomain belum dipakai siapa pun
        $request->validate([
            'store_name' => [
                'required', 
                'string', 
                'max:30', 
                'alpha_dash',
                Rule::unique('tenants', 'id')
            ],
        ]);

        $domainName = Str::slug($request->store_name);

        // CREATE TENANT: Stancl/Tenancy otomatis handle pembuatan DB tenant_domainName
        $tenant = Tenant::create([
            'id' => $domainName,
            'user_id' => auth()->id(), // Relasi Owner ke Central User
        ]);

        // CREATE DOMAIN
        $tenant->domains()->create([
            'domain' => $domainName . '.localhost',
        ]);

        return redirect()->back()->with('message', 'Toko Lu Berhasil Dibuat!');
    }
}