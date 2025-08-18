<?php

namespace App\Actions\Social;

class GetEnabledProvidersAction
{
    public function execute(): array
    {
        return collect(config('app.social_providers', []))
            ->filter(fn ($config) => ($config['enabled'] ?? false) === true)
            ->map(fn ($config) => ['name' => $config['name']])
            ->toArray();
    }
}
