<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@saucebase.dev'],
            [
                'name' => 'Chef Saucier',
                'password' => 'secretsauce',
                'email_verified_at' => now(),
            ]
        );

        // Ensure admin has the admin role
        if (! $adminUser->hasRole(Role::ADMIN)) {
            $adminUser->assignRole(Role::ADMIN);
        }
    }
}
