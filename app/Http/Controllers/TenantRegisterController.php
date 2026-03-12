<?php
namespace App\Http\Controllers;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TenantRegisterController extends Controller {
    public function index() {
        return Inertia::render('Welcome', [
            'tenants' => Tenant::with('domains')->get(),
            'auth' => ['user' => Auth::user()]
        ]);
    }
    public function store(Request $request) {
        $request->validate(['store_name' => 'required|alpha_dash|unique:tenants,id']);
        $tenantId = strtolower($request->store_name);
        $tenant = Tenant::create(['id' => $tenantId, 'user_id' => Auth::id()]);
        $tenant->domains()->create(['domain' => $tenantId . '.localhost']);
        return back();
    }
}
