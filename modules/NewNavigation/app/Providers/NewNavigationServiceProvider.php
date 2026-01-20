<?php

namespace Modules\NewNavigation\Providers;

use App\Providers\ModuleServiceProvider;

class NewNavigationServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'NewNavigation';

    protected string $nameLower = 'newnavigation';

    protected array $providers = [
        RouteServiceProvider::class,
    ];
}
