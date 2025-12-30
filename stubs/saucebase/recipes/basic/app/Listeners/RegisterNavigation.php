<?php

namespace ___MODULE_NAMESPACE___\___Module___\Listeners;

use App\Events\NavigationRegistering;
use Spatie\Navigation\Facades\Navigation;
use Spatie\Navigation\Section;

class RegisterNavigation
{
    /**
     * Handle the event.
     */
    public function handle(NavigationRegistering $event): void
    {
        // User menu - Logout
        Navigation::add('{Module}', route('{module-}.index'), function (Section $section) {
            $section->attributes([
                'group' => 'main',
                'badge' => [
                    'content' => 'New',
                    'variant' => 'info',
                ],
            ]);
        });
    }
}
