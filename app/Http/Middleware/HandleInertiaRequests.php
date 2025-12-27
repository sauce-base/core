<?php

namespace App\Http\Middleware;

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
        $nav = app(Navigation::class);

        return array_merge(parent::share($request), [
            'locale' => app()->getLocale(),
            'navigation' => [
                'main' => $nav->treeByGroup('main'),
                'secondary' => $nav->treeByGroup('secondary'),
                'settings' => $nav->treeByGroup('settings'),
                'user' => $nav->treeByGroup('user'),
            ],
            'breadcrumbs' => $nav->breadcrumbs(),
        ]);
    }
}
