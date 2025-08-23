<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    expect($user->last_login_at)->toBeNull();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user->refresh();
    expect($user->last_login_at)->not()->toBeNull();
    expect($user->last_login_at->isAfter(now()->subSeconds(5)))->toBeTrue();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('last_login_at is updated on successful authentication', function () {
    $user = User::factory()->create();

    // First login
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $user->refresh();
    $firstLogin = $user->last_login_at;
    expect($firstLogin)->not()->toBeNull();

    // Logout and login again after a brief delay
    $this->post('/logout');
    sleep(1);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $user->refresh();
    expect($user->last_login_at)->not()->toBeNull();
    expect($user->last_login_at->isAfter($firstLogin))->toBeTrue();
});
