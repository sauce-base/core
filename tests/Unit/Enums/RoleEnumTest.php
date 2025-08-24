<?php

namespace Tests\Unit\Enums;

use App\Enums\Role;
use Tests\TestCase;

class RoleEnumTest extends TestCase
{
    public function test_role_enum_values()
    {
        $this->assertEquals('admin', Role::ADMIN->value);
        $this->assertEquals('user', Role::USER->value);
    }

    public function test_role_from_string_method()
    {
        // Test valid role strings
        $this->assertEquals(Role::ADMIN, Role::fromString('admin'));
        $this->assertEquals(Role::USER, Role::fromString('user'));

        // Test invalid/null strings default to User
        $this->assertEquals(Role::USER, Role::fromString('invalid'));
        $this->assertEquals(Role::USER, Role::fromString(null));
        $this->assertEquals(Role::USER, Role::fromString(''));
        $this->assertEquals(Role::USER, Role::fromString('Admin')); // Case sensitive
    }
}
