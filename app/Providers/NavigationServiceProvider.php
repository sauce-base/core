<?php

namespace App\Providers;

use App\Services\Navigation;
use Illuminate\Support\ServiceProvider;
use Spatie\Navigation\Helpers\ActiveUrlChecker;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Navigation is now shared via HandleInertiaRequests middleware
        // This ensures it's evaluated after all navigation items are registered
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Override Spatie's scoped binding with our custom Navigation class
        // Using scoped() to match Spatie's binding type and ensure proper lifecycle
        $this->app->scoped(\Spatie\Navigation\Navigation::class, function ($app) {
            return new Navigation($app->make(ActiveUrlChecker::class));
        });
    }
}
