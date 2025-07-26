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

        $response = $this->actingAs($admin)->get('/admin/test');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Test/AdminTest')
            ->has('user')
            ->where('message', 'Welcome to the Admin Test Area, Chef Saucier! 🍯👨‍🍳')
        );
    }

    public function test_regular_user_cannot_access_admin_area()
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->get('/admin/test');

        $response->assertStatus(403); // Forbidden
    }

    public function test_editor_cannot_access_admin_area()
    {
        $editor = User::factory()->editor()->create();

        $response = $this->actingAs($editor)->get('/admin/test');

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_and_editor_can_access_editor_area()
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->editor()->create();

        // Test admin access
        $response = $this->actingAs($admin)->get('/editor/test');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Test/EditorTest'));

        // Test editor access
        $response = $this->actingAs($editor)->get('/editor/test');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Test/EditorTest'));
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
        $editor = User::factory()->editor()->create();
        $author = User::factory()->author()->create();
        $user = User::factory()->user()->create();

        // Test all role types can access user area
        foreach ([$admin, $editor, $author, $user] as $testUser) {
            $response = $this->actingAs($testUser)->get('/user/test');
            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page
                ->component('Test/UserTest')
                ->has('user')
                ->has('role')
                ->where('message', 'Welcome to the User Test Area! 👤')
            );
        }
    }

    public function test_unauthenticated_users_cannot_access_protected_areas()
    {
        // Test admin area
        $response = $this->get('/admin/test');
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
