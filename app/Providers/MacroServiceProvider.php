<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Inertia\Response;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * Register all application-wide macros here to keep them centralized.
     */
    public function boot(): void
    {
        $this->registerInertiaMacros();
    }

    /**
     * Register Inertia-specific macros.
     */
    protected function registerInertiaMacros(): void
    {
        // Add ->withSSR() method to Inertia Response for opt-in SSR
        Response::macro('withSSR', function () {
            /** @var Response $this */
            Config::set('inertia.ssr.enabled', true);

            return $this;
        });

        // Add ->withoutSSR() method to Inertia Response for opt-out SSR
        Response::macro('withoutSSR', function () {
            /** @var Response $this */
            Config::set('inertia.ssr.enabled', false);

            return $this;
        });
    }
}
