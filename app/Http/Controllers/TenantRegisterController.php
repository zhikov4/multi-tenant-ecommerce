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
        $validated = $request->validate([
            'store_name' => 'required|string|alpha_dash|max:50|unique:domains,domain',
        ], [
            'store_name.alpha_dash' => 'Nama toko hanya boleh berisi huruf, angka, strip, dan garis bawah tanpa spasi.',
            'store_name.unique' => 'Nama toko ini sudah digunakan, silakan pilih nama lain.'
        ]);

        $tenantId = strtolower($validated['store_name']);

        $tenant = Tenant::create([
            'id' => $tenantId,
        ]);

        $tenant->domains()->create([
            'domain' => $tenantId . '.localhost',
        ]);

        return Inertia::location('http://' . $tenantId . '.localhost:8000/register');
    }
}