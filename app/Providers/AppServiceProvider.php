<?php

namespace App\Providers;

use App\Facades\Navigation;
use Illuminate\Foundation\Events\DiscoverEvents;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Spatie\Navigation\Section;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->configureSecureUrls();

        /**
         * Fix for event discovery paths in modules
         *
         * @link https://github.com/nWidart/laravel-modules/issues/2128#issuecomment-3515275319
         */
        $this->fixDiscoverEventsModulePathIssue();

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
        Navigation::add('Dashboard', route('dashboard'), function (Section $section) {
            $section->attributes([
                'group' => 'main',
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'lucide:square-terminal',
                'order' => 0,
            ]);
        });
    }

    protected function fixDiscoverEventsModulePathIssue(): void
    {
        DiscoverEvents::guessClassNamesUsing(function (\SplFileInfo $file, $basePath) {
            $class = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

            // Remove the "app" folder from the path if it exists (useful for module structures)
            $appFolder = Str::of(config('modules.app_folder', 'app/'))
                ->start(DIRECTORY_SEPARATOR)
                ->finish(DIRECTORY_SEPARATOR);

            return ucfirst(Str::camel(str_replace(
                [$appFolder, DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
                ['\\', '\\', app()->getNamespace(), ''],
                ucfirst(Str::replaceLast('.php', '', $class))
            )));
        });
    }
}
