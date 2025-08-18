<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\ResetPasswordAction;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ResetPasswordActionTest extends TestCase
{
    use RefreshDatabase;

    private ResetPasswordAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ResetPasswordAction;
    }

    public function test_successfully_resets_password_with_valid_token(): void
    {
        Event::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        $result = $this->action->execute('test@example.com', 'newPassword123', $token);

        $this->assertTrue($result);

        $user->refresh();
        $this->assertTrue(Hash::check('newPassword123', $user->password));

        Event::assertDispatched(PasswordReset::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    public function test_throws_validation_exception_for_invalid_email(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('invalid-email', 'newPassword123', 'token');
    }

    public function test_throws_validation_exception_for_missing_token(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('test@example.com', 'newPassword123', '');
    }

    public function test_throws_validation_exception_for_weak_password(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('test@example.com', '123', 'token');
    }

    public function test_throws_validation_exception_for_invalid_token(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->expectException(ValidationException::class);

        $this->action->execute('test@example.com', 'newPassword123', 'invalid-token');
    }
}
