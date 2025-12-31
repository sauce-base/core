<?php

namespace App\Listeners;

use App\Events\NavigationRegistering;
use Illuminate\Support\Facades\Auth;
use Spatie\Navigation\Facades\Navigation;
use Spatie\Navigation\Section;

class RegisterNavigation
{
    /**
     * Handle the event.
     */
    public function handle(NavigationRegistering $event): void
    {
        Navigation::add('Dashboard', route('dashboard'), function (Section $section) {
            $section->attributes([
                'group' => 'main',
                'slug' => 'dashboard',
                'order' => 0,
            ]);
        });

        Navigation::add(
            'Star us on Github',
            'https://github.com/sauce-base/saucebase',
            function (Section $section) {
                $section->attributes([
                    'group' => 'secondary',
                    'slug' => 'github',
                    'external' => true,
                    'newPage' => true,
                    'order' => 0,
                ]);
            }
        );

        Navigation::addWhen(
            fn () => Auth::check() && Auth::user()->isAdmin(),
            'Admin',
            route('filament.admin.pages.dashboard'),
            function (Section $section) {
                $section->attributes([
                    'group' => 'secondary',
                    'slug' => 'admin',
                    'order' => 10,
                    'external' => true,
                    'newPage' => true,
                    'class' => 'bg-yellow-500/10 text-yellow-600 hover:bg-yellow-500/20 hover:text-yellow-400',
                ]);
            }
        );
    }
}
