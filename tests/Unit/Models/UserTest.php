<?php

namespace Tests\Unit\Models;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolesDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles for testing
        $this->seed(RolesDatabaseSeeder::class);
    }

    public function test_is_admin_returns_true_when_user_has_admin_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::ADMIN->value);

        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_when_user_has_user_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::USER->value);

        $this->assertFalse($user->isAdmin());
    }

    public function test_is_admin_returns_false_when_user_has_no_roles(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isAdmin());
    }

    public function test_is_user_returns_true_when_user_has_user_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::USER->value);

        $this->assertTrue($user->isUser());
    }

    public function test_is_user_returns_false_when_user_has_admin_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::ADMIN->value);

        $this->assertFalse($user->isUser());
    }

    public function test_is_user_returns_false_when_user_has_no_roles(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isUser());
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::ADMIN->value);
        $user->assignRole(RoleEnum::USER->value);

        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->isUser());
    }

    public function test_role_methods_work_with_spatie_has_roles_trait(): void
    {
        $user = User::factory()->create();
        
        // Verify hasRole method from Spatie trait works
        $this->assertFalse($user->hasRole(RoleEnum::ADMIN->value));
        
        $user->assignRole(RoleEnum::ADMIN->value);
        
        // Verify hasRole method returns true after assignment
        $this->assertTrue($user->hasRole(RoleEnum::ADMIN->value));
        
        // Verify our isAdmin method uses hasRole correctly
        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_and_is_user_are_mutually_exclusive_when_single_role(): void
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole(RoleEnum::ADMIN->value);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($adminUser->isUser());

        $regularUser = User::factory()->create();
        $regularUser->assignRole(RoleEnum::USER->value);

        $this->assertFalse($regularUser->isAdmin());
        $this->assertTrue($regularUser->isUser());
    }
}
