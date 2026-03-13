<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantRegisterController extends Controller
{
    public function store(Request $request)
    {
        // Ubah nama toko jadi slug dulu sebelum di-cek ke database
        $slug = Str::slug($request->store_name);
        $request->merge(['domain_id' => $slug]);

        // Validasi ketat untuk semua step
        $request->validate([
            'store_display_name' => 'required|string|max:255',
            'domain_id'          => 'required|string|max:255|unique:tenants,id', // Cek apakah slug sudah dipakai
            'domain_extension'   => 'required',
            'description'        => 'required|string',
            'category'           => 'required',
            'country_code'       => 'required',
            'phone'              => 'required|string',
            'address_detail'     => 'required|string',
            'zip_code'           => 'required|string',
            'agreement'          => 'accepted',
        ], [
            // Custom error message (Professional English)
            'domain_id.unique' => 'This Domain Slug is already assigned to an active node. Please select a unique identifier.',
        ]);

        try {
            $tenant = Tenant::create([
                'id'      => $slug,
                'user_id' => auth()->id(),
                'data'    => [
                    'store_name'     => $request->store_display_name,
                    'category'       => $request->category,
                    'description'    => $request->description,
                    'phone'          => $request->country_code . $request->phone,
                    'address_detail' => $request->address_detail,
                    'zip_code'       => $request->zip_code,
                ]
            ]);

            $tenant->domains()->create([
                'domain' => $slug . $request->domain_extension,
            ]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('dashboard');
        }
    }
}