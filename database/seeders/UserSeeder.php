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

        // Create additional test users for local/testing environments
        if (app()->environment(['local', 'testing'])) {
            $this->createTestUsers();
        }
    }

    /**
     * Create additional test users for E2E testing
     */
    private function createTestUsers(): void
    {

        // Create regular user for testing
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => 'secretsauce',
                'email_verified_at' => now(),
            ]
        );
        if (! $user->hasRole(Role::USER)) {
            $user->assignRole(Role::USER);
        }

        // Create additional test user
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'secretsauce',
                'email_verified_at' => now(),
            ]
        );
        if (! $testUser->hasRole(Role::USER)) {
            $testUser->assignRole(Role::USER);
        }
    }
}
