<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\LogoutAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutActionTest extends TestCase
{
    use RefreshDatabase;

    private LogoutAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new LogoutAction;
    }

    public function test_successfully_logs_out_authenticated_user(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->assertTrue(Auth::check());

        $this->action->execute();

        $this->assertFalse(Auth::check());
        $this->assertNull(Auth::user());
    }

    public function test_logout_when_no_user_authenticated(): void
    {
        $this->assertFalse(Auth::check());

        $this->action->execute();

        $this->assertFalse(Auth::check());
    }
}
