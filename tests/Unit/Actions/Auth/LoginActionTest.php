<?php

use App\Actions\Auth\LoginAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new LoginAction;
});

test('successfully logs in user with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    expect($user->last_login_at)->toBeNull();

    $result = $this->action->execute('test@example.com', 'password123');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
    expect(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($user->id);

    $user->refresh();
    expect($user->last_login_at)->not->toBeNull()
        ->and($user->last_login_at->isAfter(now()->subSeconds(5)))->toBeTrue();
});

test('logs in user with remember option', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    expect($user->last_login_at)->toBeNull();

    $result = $this->action->execute('test@example.com', 'password123', true);

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
    expect(Auth::check())->toBeTrue();

    $user->refresh();
    expect($user->last_login_at)->not->toBeNull()
        ->and($user->last_login_at->isAfter(now()->subSeconds(5)))->toBeTrue();
});

test('throws validation exception for invalid email', function () {
    $this->action->execute('invalid-email', 'password123');
})->throws(ValidationException::class);

test('throws validation exception for missing email', function () {
    $this->action->execute('', 'password123');
})->throws(ValidationException::class);

test('throws validation exception for missing password', function () {
    $this->action->execute('test@example.com', '');
})->throws(ValidationException::class);

test('throws validation exception for wrong credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $this->action->execute('test@example.com', 'wrong-password');
})->throws(ValidationException::class, 'These credentials do not match our records.');

test('throws validation exception for non existent user', function () {
    $this->action->execute('nonexistent@example.com', 'password123');
})->throws(ValidationException::class, 'These credentials do not match our records.');
