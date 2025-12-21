<?php

namespace Tests\Unit\Services\Navigation;

use App\Services\Navigation\NavigationRegistry;
use Tests\TestCase;

class NavigationRegistryTest extends TestCase
{
    private NavigationRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new NavigationRegistry;
    }

    public function test_can_add_navigation_items_to_app(): void
    {
        $this->registry->app()->add('Home', '/home');

        $tree = $this->registry->app()->tree();

        $this->assertNotEmpty($tree);
        $this->assertEquals('Home', $tree[0]['title']);
        $this->assertEquals('/home', $tree[0]['url']);
    }

    public function test_can_add_navigation_items_to_settings(): void
    {
        $this->registry->settings()->add('Profile', '/settings/profile');

        $tree = $this->registry->settings()->tree();

        $this->assertNotEmpty($tree);
        $this->assertEquals('Profile', $tree[0]['title']);
        $this->assertEquals('/settings/profile', $tree[0]['url']);
    }

    public function test_can_add_navigation_items_to_user(): void
    {
        $this->registry->user()->add('Logout', '/logout');

        $tree = $this->registry->user()->tree();

        $this->assertNotEmpty($tree);
        $this->assertEquals('Logout', $tree[0]['title']);
        $this->assertEquals('/logout', $tree[0]['url']);
    }

    public function test_add_if_adds_item_when_condition_is_true(): void
    {
        $this->registry->app()->addIf(true, 'Dashboard', '/dashboard');

        $tree = $this->registry->app()->tree();

        $this->assertCount(1, $tree);
        $this->assertEquals('Dashboard', $tree[0]['title']);
    }

    public function test_add_if_does_not_add_item_when_condition_is_false(): void
    {
        $this->registry->app()->addIf(false, 'Dashboard', '/dashboard');

        $tree = $this->registry->app()->tree();

        $this->assertEmpty($tree);
    }

    public function test_can_chain_multiple_add_calls(): void
    {
        $this->registry->app()
            ->add('Home', '/home')
            ->add('About', '/about')
            ->add('Contact', '/contact');

        $tree = $this->registry->app()->tree();

        $this->assertCount(3, $tree);
        $this->assertEquals('Home', $tree[0]['title']);
        $this->assertEquals('About', $tree[1]['title']);
        $this->assertEquals('Contact', $tree[2]['title']);
    }

    public function test_navigation_groups_are_independent(): void
    {
        $this->registry->app()->add('Dashboard', '/dashboard');
        $this->registry->settings()->add('Profile', '/settings/profile');
        $this->registry->user()->add('Logout', '/logout');

        $appTree = $this->registry->app()->tree();
        $settingsTree = $this->registry->settings()->tree();
        $userTree = $this->registry->user()->tree();

        $this->assertCount(1, $appTree);
        $this->assertCount(1, $settingsTree);
        $this->assertCount(1, $userTree);
        $this->assertEquals('Dashboard', $appTree[0]['title']);
        $this->assertEquals('Profile', $settingsTree[0]['title']);
        $this->assertEquals('Logout', $userTree[0]['title']);
    }

    public function test_can_add_navigation_items_with_callback(): void
    {
        $this->registry->app()->add('Dashboard', '/dashboard', function ($section) {
            $section->attributes(['icon' => 'dashboard-icon']);
        });

        $tree = $this->registry->app()->tree();

        $this->assertNotEmpty($tree);
        $this->assertEquals('Dashboard', $tree[0]['title']);
        $this->assertEquals('dashboard-icon', $tree[0]['attributes']['icon']);
    }
}
