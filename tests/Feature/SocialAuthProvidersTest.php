<?php

test('providers endpoint returns only enabled providers', function () {
    config(['app.social_providers' => [
        'github' => [
            'name' => 'GitHub',
            // 'enabled' key intentionally missing to ensure defaults to false
        ],
        'google' => [
            'enabled' => true,
            'name' => 'Google',
        ],
    ]]);

    $response = $this->getJson('/auth/providers');

    $response->assertOk()
        ->assertJson([
            'providers' => [
                'google' => ['name' => 'Google'],
            ],
        ]);

    expect($response->json('providers'))->toHaveKey('google');
    expect($response->json('providers'))->not->toHaveKey('github');
});
