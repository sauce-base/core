<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'socialite' => [ // TODO: check if is used
        'connect_with' => 'Connect with :Provider',
        'disconnect' => 'Disconnect',
        'not_connected' => 'Not connected',
        'connected' => 'Connected',
        'account_connected' => ':Provider account connected successfully.',
        'account_disconnected' => ':Provider account disconnected successfully.',
        'error' => 'An error occurred while processing your social account.',
        'invalid_provider' => 'The selected social provider is invalid or not supported.',
    ],
];
