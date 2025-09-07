<?php

return [
    'name' => 'Auth',
    /*
    |--------------------------------------------------------------------------
    | Social Login Providers
    |--------------------------------------------------------------------------
    |
    | Configuration for social authentication providers. Each provider can be
    | enabled/disabled and configured with display information for the frontend.
    |
    | The order of the providers in this config file determines the order
    | in which they will be displayed on the frontend.
    |
    */

    'social_providers' => [
        'github' => [
            'enabled' => env('GITHUB_LOGIN_ENABLED', false),
            'name' => 'GitHub',
            'provider' => \SocialiteProviders\GitHub\Provider::class,
        ],
        'google' => [
            'enabled' => env('GOOGLE_LOGIN_ENABLED', true),
            'name' => 'Google',
            'provider' => \SocialiteProviders\Google\Provider::class,
        ],
    ],
];
