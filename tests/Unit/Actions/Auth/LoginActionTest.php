<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\LoginAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    use RefreshDatabase;

    private LoginAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new LoginAction;
    }

    public function test_successfully_logs_in_user_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->action->execute('test@example.com', 'password123');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_logs_in_user_with_remember_option(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->action->execute('test@example.com', 'password123', true);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
        $this->assertTrue(Auth::check());
    }

    public function test_throws_validation_exception_for_invalid_email(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('invalid-email', 'password123');
    }

    public function test_throws_validation_exception_for_missing_email(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('', 'password123');
    }

    public function test_throws_validation_exception_for_missing_password(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('test@example.com', '');
    }

    public function test_throws_validation_exception_for_wrong_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('These credentials do not match our records.');

        $this->action->execute('test@example.com', 'wrong-password');
    }

    public function test_throws_validation_exception_for_non_existent_user(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('These credentials do not match our records.');

        $this->action->execute('nonexistent@example.com', 'password123');
    }
}
