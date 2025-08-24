<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    public function test_admin_can_access_admin_area()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/manage/test');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('test/AdminTest')
            ->has('user')
            ->where('message', 'Welcome to the Admin Test Area, Chef Saucier! 🍯👨‍🍳')
        );
    }

    public function test_regular_user_cannot_access_admin_area()
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->get('/manage/test');

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_access_editor_area()
    {
        $admin = User::factory()->admin()->create();

        // Test admin access
        $response = $this->actingAs($admin)->get('/editor/test');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('test/EditorTest'));
    }

    public function test_regular_user_cannot_access_editor_area()
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->get('/editor/test');

        $response->assertStatus(403); // Forbidden
    }

    public function test_all_authenticated_users_can_access_user_area()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        // Test all role types can access user area
        foreach ([$admin, $user] as $testUser) {
            $response = $this->actingAs($testUser)->get('/user/test');
            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->component('test/UserTest')
                ->has('user')
                ->has('role')
                ->where('message', 'Welcome to the User Test Area! 👤')
            );
        }
    }

    public function test_unauthenticated_users_cannot_access_protected_areas()
    {
        // Test admin area
        $response = $this->get('/manage/test');
        $response->assertRedirect('/login');

        // Test editor area
        $response = $this->get('/editor/test');
        $response->assertRedirect('/login');

        // Test user area
        $response = $this->get('/user/test');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_accessible_to_authenticated_users()
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard'));
    }
}
