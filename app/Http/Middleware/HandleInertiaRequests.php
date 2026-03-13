<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            // Share flash message buat notifikasi buat toko berhasil
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
            // Share status toko ke semua komponen Vue
            'status' => fn () => $request->session()->get('status') ?? [
                'hasStore' => false,
                'storeUrl' => null,
            ],
        ]);
    }
}