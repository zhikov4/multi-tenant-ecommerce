<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TenantRegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Store/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $slug = Str::slug($request->store_name);

        $request->merge(['domain_id' => $slug]);

        $request->validate([
            'store_display_name' => 'required|string|max:255',
            'domain_id'          => 'required|string|max:255|unique:tenants,id',
            'domain_extension'   => 'required',
            'description'        => 'required|string',
            'category'           => 'required',
            'country_code'       => 'required',
            'phone'              => 'required|string',
            'address_detail'     => 'required|string',
            'zip_code'           => 'required|string',
            'agreement'          => 'accepted',
        ], [
            'domain_id.unique' => 'This store name is already taken. Please choose a different one.',
        ]);

        $tenant = Tenant::create([
            'id'      => $slug,
            'user_id' => auth()->id(),
            'data'    => [
                'tenancy_db_connection' => config('tenancy.database.template_tenant_connection'),
                'store_name'            => $request->store_display_name,
                'category'              => $request->category,
                'description'           => $request->description,
                'phone'                 => $request->country_code . $request->phone,
                'address_detail'        => $request->address_detail,
                'zip_code'              => $request->zip_code,
            ],
        ]);

        $tenant->domains()->create([
            'domain' => $slug . '.localhost',
        ]);

        return redirect()->route('dashboard')->with('success', 'Store created successfully!');
    }
}