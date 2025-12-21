<?php

namespace Tests\Unit\Models;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::ADMIN->value);

        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::USER->value);

        $this->assertFalse($user->isAdmin());
    }

    public function test_is_user_returns_true_for_user_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::USER->value);

        $this->assertTrue($user->isUser());
    }

    public function test_is_user_returns_false_for_non_user_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::ADMIN->value);

        $this->assertFalse($user->isUser());
    }
}
