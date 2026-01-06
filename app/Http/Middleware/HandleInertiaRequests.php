<?php

namespace App\Http\Middleware;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Navigation\Navigation;

class HandleInertiaRequests extends Middleware
{
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
