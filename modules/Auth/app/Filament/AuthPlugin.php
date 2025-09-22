<?php

namespace Modules\Auth\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class AuthPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Auth';
    }

    public function getId(): string
    {
        return 'auth';
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
