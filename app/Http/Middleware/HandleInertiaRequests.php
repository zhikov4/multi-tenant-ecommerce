<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();
        $userStores = [];

        if ($user) {
            $user->loadMissing('tenant.domains');

            if ($user->tenant && $user->tenant->domains->isNotEmpty()) {
                $domain = $user->tenant->domains->first()->domain;
                $port = ':8000';
                $userStores[] = [
                    'id'   => $user->tenant->id,
                    'name' => $user->tenant->getInternal('store_name') ?? $user->tenant->id,
                    'url'  => 'http://' . $domain . $port . '/products',
                ];
            }
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
            ],
            'userStores' => $userStores,
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }
}