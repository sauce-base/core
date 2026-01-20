<?php

use Spatie\Navigation\Facades\Navigation;
use Spatie\Navigation\Section;

Navigation::add('{Module}', route('{module-}.index'), function (Section $section) {
    $section->attributes([
        'group' => 'main',
        'badge' => [
            'content' => 'New',
            'variant' => 'info',
        ],
    ]);
});
