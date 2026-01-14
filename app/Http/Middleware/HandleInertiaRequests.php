<?php

namespace App\Http\Middleware;

use Closure;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Middleware;
use Spatie\Navigation\Navigation;
use Symfony\Component\HttpFoundation\Response;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * Handle the incoming request.
     *
     * Disables SSR by default for each request.
     * Controllers can opt-in using ->withSSR() or explicitly disable using ->withoutSSR()
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Disable SSR by default for this request
        // Controllers can override with ->withSSR() or ->withoutSSR()
        Config::set('inertia.ssr.enabled', false);

        return parent::handle($request, $next);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return array_merge(parent::share($request), [
            'locale' => app()->getLocale(),
            'navigation' => app(Navigation::class)->treeGrouped(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'toast' => fn () => $request->session()->pull('toast'),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }

    /**
     * Get breadcrumbs for the current request.
     * Uses diglactic/laravel-breadcrumbs if available, falls back to Spatie Navigation.
     */
    protected function getBreadcrumbs(): array
    {
        $routeName = request()->route()?->getName();

        // Try to use laravel-breadcrumbs package
        if ($routeName && Breadcrumbs::exists($routeName)) {
            try {
                $breadcrumbs = Breadcrumbs::generate($routeName, ...request()->route()->parameters());

                // Convert to array format compatible with frontend
                return collect($breadcrumbs)->map(fn (object $crumb) => [
                    'title' => 'breadcrumbs.'.$crumb->title,
                    'url' => $crumb->url,
                    'attributes' => $crumb->data ?? [],
                ])->toArray();
            } catch (\Exception $e) {
                report($e);
                // Fall through to Spatie Navigation on error
            }
        }

        // Fallback to Spatie Navigation
        return app(Navigation::class)->breadcrumbs();
    }
}
