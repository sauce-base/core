<?php

namespace Modules\Auth\Actions\Socialite;

class GetEnabledProvidersAction
{
    public function execute(): array
    {
        return collect(config('auth.social_providers', []))
            ->filter(fn($config) => ($config['enabled'] ?? false) === true)
            ->map(fn($config) => ['name' => $config['name']])
            ->toArray();
    }
}
