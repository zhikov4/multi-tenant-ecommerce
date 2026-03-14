<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $host = request()->getHost();
        $isTenant = $host !== 'localhost' && $host !== '127.0.0.1';

        if ($isTenant) {
            Config::set('app.asset_url', asset('/'));
            Config::set('session.domain', '.' . $host);
        } else {
            Config::set('session.domain', 'localhost');
        }
    }
}