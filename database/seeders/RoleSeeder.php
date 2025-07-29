<?php

namespace Database\Seeders;

use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles using the Role enum
        foreach (RoleEnum::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value], [
                'name' => $role->value,
                'guard_name' => 'web',
            ]);
        }
    }
}
