<?php

use Spatie\Navigation\Facades\Navigation;
use Spatie\Navigation\Section;

/*
|--------------------------------------------------------------------------
| {Module} Module Navigation
|--------------------------------------------------------------------------
|
| Define {Module} module navigation items here.
| These items will be loaded automatically when the module is enabled.
|
*/

Navigation::add('{Module}', route('{module-}.index'), function (Section $section) {
    $section->attributes([
        'group' => 'main',
        'badge' => [
            'content' => 'New',
            'variant' => 'info',
        ],
    ]);
});
