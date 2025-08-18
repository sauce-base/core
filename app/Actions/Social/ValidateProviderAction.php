<?php

namespace App\Actions\Social;

class ValidateProviderAction
{
    public function execute(string $provider): bool
    {
        $enabledProviders = collect(config('app.social_providers', []))
            ->filter(fn ($config) => ($config['enabled'] ?? false) === true)
            ->keys()
            ->toArray();

        return in_array($provider, $enabledProviders);
    }
}
