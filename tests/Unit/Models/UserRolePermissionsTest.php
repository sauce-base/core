<?php

namespace Tests\Unit\Models;

use App\Enums\Role;
use App\Models\User;
use Tests\TestCase;

class UserRolePermissionsTest extends TestCase
{
    public function test_user_role_assignment()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        // Test basic role assignment
        $this->assertTrue($admin->hasRole(Role::ADMIN));
        $this->assertTrue($user->hasRole(Role::USER));

        // Test helper methods
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($user->isUser());
    }

    public function test_users_have_single_role()
    {
        $admin = User::factory()->admin()->create();

        // Should have exactly one role
        $this->assertCount(1, $admin->roles);
        $this->assertEquals('admin', $admin->roles->first()->name);

        // Should not have other roles
        $this->assertFalse($admin->hasRole(Role::USER));
    }

    public function test_user_factory_method()
    {
        $user = User::factory()->user()->create();

        // Factory user() method assigns USER role
        $this->assertTrue($user->hasRole(Role::USER));
        $this->assertEquals(Role::USER, $user->getRole());
        $this->assertCount(1, $user->roles);
    }

    public function test_role_switching()
    {
        $user = User::factory()->user()->create();

        // Start with USER role
        $this->assertTrue($user->hasRole(Role::USER));

        // Switch to ADMIN role
        $user->syncRoles([Role::ADMIN]);
        $user->refresh();

        $this->assertTrue($user->hasRole(Role::ADMIN));
        $this->assertFalse($user->hasRole(Role::USER));
        $this->assertCount(1, $user->roles);
    }

    public function test_get_role_enum()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        // Test getRole method returns correct enum
        $this->assertEquals(Role::ADMIN, $admin->getRole());
        $this->assertEquals(Role::USER, $user->getRole());

        // Test enum methods work
        $this->assertEquals('Administrator', $admin->getRole()->label());
        $this->assertEquals('Basic user with limited permissions', $user->getRole()->description());
    }
}
