<?php

use Spatie\Navigation\Facades\Navigation;
use Spatie\Navigation\Section;

Navigation::add('NewNavigation', route('new-navigation.index'), function (Section $section) {
    $section->attributes([
        'group' => 'main',
        'badge' => [
            'content' => 'New',
            'variant' => 'info',
        ],
    ]);
});
