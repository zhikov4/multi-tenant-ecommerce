<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenancyServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->bootEvents();
        $this->makeRoutes();
    }

    protected function bootEvents()
    {
        Event::listen(Events\TenantCreated::class, JobPipeline::make([
            Jobs\CreateDatabase::class,
            Jobs\MigrateDatabase::class,
            Jobs\SeedDatabase::class,
        ])->send(function (Events\TenantCreated $event) {
            return $event->tenant;
        })->shouldBeQueued(false)->toListener());
    }

    protected function makeRoutes()
    {
        $centralDomains = config('tenancy.central_domains', ['localhost']);
        $currentHost = request()->getHost();

        if (!in_array($currentHost, $centralDomains)) {
            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ])->group(base_path('routes/tenant.php'));
        }
    }
}