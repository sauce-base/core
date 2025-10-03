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
            'name' => 'GitHub',
            'enabled' => env('GITHUB_LOGIN_ENABLED', false),
        ],
        'google' => [
            'name' => 'Google',
            'enabled' => env('GOOGLE_LOGIN_ENABLED', true),
        ],
    ],
];
