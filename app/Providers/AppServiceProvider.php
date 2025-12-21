<?php

namespace App\Providers;

use App\Services\Navigation\NavigationRegistry;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Navigation\Section;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureSecureUrls();

        // Register navigation after routes are loaded
        $this->app->booted(function () {
            $this->registerNavigation();
        });
    }

    protected function configureSecureUrls()
    {
        // Determine if HTTPS should be enforced
        $enforceHttps = $this->app->environment(['production', 'staging'])
            && ! $this->app->runningUnitTests();

        // For local development with SSL setup
        $localHttps = $this->app->environment('local')
            && config('app.url')
            && str_starts_with(config('app.url'), 'https://')
            && ! $this->app->runningUnitTests();

        $useHttps = $enforceHttps || $localHttps;

        // Force HTTPS for all generated URLs
        URL::forceHttps($useHttps);

        // Ensure proper server variable is set
        if ($useHttps) {
            $this->app['request']->server->set('HTTPS', 'on');
        }

        // Set up global middleware for security headers in production/staging
        if ($enforceHttps) {
            $this->app['router']->pushMiddlewareToGroup('web', function ($request, $next) {
                $response = $next($request);

                return $response->withHeaders([
                    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
                    'Content-Security-Policy' => 'upgrade-insecure-requests',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            });
        }
    }

    /**
     * Register navigation items for core features.
     */
    protected function registerNavigation(): void
    {
        $registry = app(NavigationRegistry::class);

        // Use URL instead of route() to avoid exceptions when route doesn't exist yet
        $registry->app()
            ->add('Dashboard', '/dashboard', function (Section $section) {
                $section->attributes([
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'square-terminal',
                    'order' => 0,
                ]);
            });
    }
}
