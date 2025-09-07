<?php

namespace Modules\Auth\Actions\Socialite;

class ValidateProviderAction
{
    public function execute(string $provider): bool
    {
        $enabledProviders = collect(config('auth.social_providers', []))
            ->filter(fn($config) => ($config['enabled'] ?? false) === true)
            ->keys()
            ->toArray();

        return in_array($provider, $enabledProviders);
    }
}
