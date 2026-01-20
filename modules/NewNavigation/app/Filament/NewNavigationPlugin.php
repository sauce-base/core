<?php

namespace Modules\NewNavigation\Filament;

use App\Filament\ModulePlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class NewNavigationPlugin implements Plugin
{
    use ModulePlugin;

    public function getModuleName(): string
    {
        return 'NewNavigation';
    }

    public function getId(): string
    {
        return 'newnavigation';
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
