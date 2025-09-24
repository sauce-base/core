<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(Tests\TestCase::class);

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

test('social login updates last_login_at timestamp', function () {
    // Mock the social provider response
    $socialiteUser = mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456');
    $socialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $socialiteUser->shouldReceive('getName')->andReturn('Test User');
    $socialiteUser->shouldReceive('getAvatar')->andReturn('https://avatar.example.com/test.jpg');

    $provider = mock('Laravel\Socialite\Contracts\Provider');
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

    // Create existing user
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    // Create social account for the user
    $user->socialAccounts()->create([
        'provider' => 'github',
        'provider_id' => '123456',
        'provider_avatar_url' => 'https://avatar.example.com/test.jpg',
    ]);

    expect($user->last_login_at)->toBeNull();

    // Test social login callback
    config(['app.social_providers.github.enabled' => true]);

    $this->get('/auth/github/callback');

    $this->assertAuthenticated();
    expect(Auth::id())->toBe($user->id);

    $user->refresh();
    expect($user->last_login_at)->not()->toBeNull();
    expect($user->last_login_at->isAfter(now()->subSeconds(5)))->toBeTrue();
});
