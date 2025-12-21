<?php

namespace Tests\Unit\Enums;

use App\Enums\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_admin_role_has_correct_value(): void
    {
        $this->assertEquals('admin', Role::ADMIN->value);
    }

    public function test_user_role_has_correct_value(): void
    {
        $this->assertEquals('user', Role::USER->value);
    }

    public function test_admin_label_returns_correct_text(): void
    {
        $this->assertEquals('Administrator', Role::ADMIN->label());
    }

    public function test_user_label_returns_correct_text(): void
    {
        $this->assertEquals('User', Role::USER->label());
    }

    public function test_values_returns_all_role_values(): void
    {
        $values = Role::values();

        $this->assertIsArray($values);
        $this->assertContains('admin', $values);
        $this->assertContains('user', $values);
        $this->assertCount(2, $values);
    }

    public function test_labels_returns_all_role_labels(): void
    {
        $labels = Role::labels();

        $this->assertIsArray($labels);
        $this->assertContains('Administrator', $labels);
        $this->assertContains('User', $labels);
        $this->assertCount(2, $labels);
    }

    public function test_from_string_returns_admin_role(): void
    {
        $role = Role::fromString('admin');

        $this->assertEquals(Role::ADMIN, $role);
    }

    public function test_from_string_returns_user_role(): void
    {
        $role = Role::fromString('user');

        $this->assertEquals(Role::USER, $role);
    }

    public function test_from_string_defaults_to_user_for_invalid_role(): void
    {
        $role = Role::fromString('invalid_role');

        $this->assertEquals(Role::USER, $role);
    }

    public function test_from_string_defaults_to_user_for_null(): void
    {
        $role = Role::fromString(null);

        $this->assertEquals(Role::USER, $role);
    }
}
