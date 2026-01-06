<?php

namespace ___MODULE_NAMESPACE___\___Module___\Providers;

use App\Providers\ModuleServiceProvider;

class ___Module___ServiceProvider extends ModuleServiceProvider
{
    protected string $name = '{Module}';

    protected string $nameLower = '{module}';

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
