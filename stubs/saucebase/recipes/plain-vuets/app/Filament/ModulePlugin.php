<?php

namespace ___MODULE_NAMESPACE___\___Module___\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class ___Module___Plugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return '{Module}';
    }

    public function getId(): string
    {
        return '{module}';
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
