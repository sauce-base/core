<?php

namespace Modules\Settings\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingsPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Settings';
    }

    public function getId(): string
    {
        return 'settings';
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
