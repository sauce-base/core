<?php

namespace App\Providers;

use App\Services\Navigation\NavigationRegistry;
use App\Services\Navigation\NavigationTransformer;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Share navigation data with Inertia
        Inertia::share([
            'navigation' => fn () => $this->getNavigationTrees(),
            'breadcrumbs' => fn () => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Get navigation trees with caching for performance.
     */
    protected function getNavigationTrees(): array
    {
        // Cache navigation trees per request to avoid rebuilding multiple times
        return once(function () {
            $transformer = app(NavigationTransformer::class);
            $registry = app(NavigationRegistry::class);

            return [
                'app' => $transformer->transform($registry->app()->tree()),
                'settings' => $transformer->transform($registry->settings()->tree()),
                'user' => $transformer->transform($registry->user()->tree()),
            ];
        });
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
        } catch (\Spatie\Navigation\Exceptions\CouldNotDetermineBreadcrumbs $e) {
            // Page not in navigation - this is expected for pages without breadcrumbs
            return [];
        } catch (\Exception $e) {
            // Log unexpected errors for debugging
            logger()->warning('Failed to generate breadcrumbs', [
                'error' => $e->getMessage(),
                'url' => request()->url(),
            ]);

            return [];
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
