<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Jika kita sedang di subdomain (tenant), paksa aset ke path absolut
        if (request()->getHost() !== 'localhost' && request()->getHost() !== '127.0.0.1') {
            Config::set('app.asset_url', asset('/'));
        }
    }
}