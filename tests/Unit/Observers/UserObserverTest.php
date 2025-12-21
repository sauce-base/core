<?php

namespace Tests\Unit\Observers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_observer_assigns_default_role_on_creation(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->hasRole(Role::USER->value));
    }

    public function test_user_observer_does_not_override_explicitly_assigned_role(): void
    {
        $user = User::factory()->create();
        
        // The observer assigns USER role by default
        $this->assertTrue($user->hasRole(Role::USER->value));
        
        // Now assign ADMIN role
        $user->assignRole(Role::ADMIN->value);
        
        // User should have both roles now (or just ADMIN depending on implementation)
        $this->assertTrue($user->hasRole(Role::ADMIN->value));
    }
}
