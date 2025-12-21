<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Services\Navigation\NavigationRegistry;
use App\Services\Navigation\NavigationTransformer;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Share navigation data with Inertia
        Inertia::share([
            'navigation' => fn () => [
                'app' => app(NavigationTransformer::class)->transform(app(NavigationRegistry::class)->app()->tree()),
                'settings' => app(NavigationTransformer::class)->transform(app(NavigationRegistry::class)->settings()->tree()),
                'user' => app(NavigationTransformer::class)->transform(app(NavigationRegistry::class)->user()->tree()),
            ],
            'breadcrumbs' => fn () => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Get breadcrumbs from navigation hierarchy.
     */
    protected function getBreadcrumbs(): array
    {
        try {
            $breadcrumbs = app(NavigationRegistry::class)->app()->breadcrumbs();

            // Transform inline: { title, url, attributes } -> { label, url }
            return array_map(function ($item) {
                return [
                    'label' => $item['attributes']['label'] ?? $item['title'],
                    'url' => $item['url'],
                ];
            }, $breadcrumbs);
        } catch (\Exception $e) {
            return []; // Page not in navigation
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Register NavigationRegistry as a singleton
        $this->app->singleton(NavigationRegistry::class, function ($app) {
            return new NavigationRegistry;
        });

        // Register NavigationTransformer as a singleton
        $this->app->singleton(NavigationTransformer::class, function ($app) {
            return new NavigationTransformer;
        });
    }
}
