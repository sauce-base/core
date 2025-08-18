<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RegisterUserAction;
    }

    public function test_successfully_creates_new_user(): void
    {
        Event::fake();

        $user = $this->action->execute('John Doe', 'john@example.com', 'password123');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        Event::assertDispatched(Registered::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    public function test_throws_validation_exception_for_missing_name(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('', 'john@example.com', 'password123');
    }

    public function test_throws_validation_exception_for_invalid_email(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('John Doe', 'invalid-email', 'password123');
    }

    public function test_throws_validation_exception_for_missing_email(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('John Doe', '', 'password123');
    }

    public function test_throws_validation_exception_for_duplicate_email(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $this->expectException(ValidationException::class);

        $this->action->execute('John Doe', 'john@example.com', 'password123');
    }

    public function test_throws_validation_exception_for_weak_password(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('John Doe', 'john@example.com', '123');
    }

    public function test_throws_validation_exception_for_missing_password(): void
    {
        $this->expectException(ValidationException::class);

        $this->action->execute('John Doe', 'john@example.com', '');
    }

    public function test_converts_email_to_lowercase(): void
    {
        Event::fake();

        $user = $this->action->execute('John Doe', 'JOHN@EXAMPLE.COM', 'password123');

        $this->assertEquals('john@example.com', $user->email);
    }

    public function test_name_respects_max_length(): void
    {
        $this->expectException(ValidationException::class);

        $longName = str_repeat('a', 256);
        $this->action->execute($longName, 'john@example.com', 'password123');
    }
}
