<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spatie\Navigation\Navigation add(string $title, string|\Closure $url, \Closure $configurator = null)
 * @method static array tree()
 * @method static array breadcrumbs()
 * @method static array treeByGroup(string $group)
 *
 * @see \App\Services\Navigation\Navigation
 */
class Navigation extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Spatie\Navigation\Navigation::class;
    }
}
