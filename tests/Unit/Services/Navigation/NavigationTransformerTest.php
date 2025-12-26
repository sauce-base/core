<?php

namespace Tests\Unit\Services\Navigation;

use App\Services\Navigation\NavigationTransformer;
use PHPUnit\Framework\TestCase;

class NavigationTransformerTest extends TestCase
{
    private NavigationTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new NavigationTransformer;
    }

    public function test_transforms_spatie_tree_to_menu_item_format(): void
    {
        $spatieTree = [
            [
                'url' => 'https://example.com/dashboard',
                'title' => 'Dashboard',
                'active' => true,
                'attributes' => [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'square-terminal',
                ],
                'children' => [],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertCount(1, $result);
        $this->assertEquals('Dashboard', $result[0]['label']);
        $this->assertEquals('https://example.com/dashboard', $result[0]['url']);
        $this->assertEquals('dashboard', $result[0]['route']);
        $this->assertEquals('square-terminal', $result[0]['icon']);
        $this->assertTrue($result[0]['active']);
    }

    public function test_extracts_action_from_attributes(): void
    {
        $spatieTree = [
            [
                'url' => '#',
                'title' => 'Log out',
                'active' => false,
                'attributes' => [
                    'label' => 'Log out',
                    'action' => 'logout',
                    'icon' => 'log-out',
                ],
                'children' => [],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertEquals('logout', $result[0]['action']);
        $this->assertEquals('log-out', $result[0]['icon']);
    }

    public function test_preserves_type_from_attributes(): void
    {
        $spatieTree = [
            [
                'url' => '',
                'title' => 'Separator',
                'active' => false,
                'attributes' => [
                    'type' => 'separator',
                ],
                'children' => [],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertEquals('separator', $result[0]['type']);
    }

    public function test_transforms_children_recursively(): void
    {
        $spatieTree = [
            [
                'url' => 'https://example.com/admin',
                'title' => 'Admin',
                'active' => false,
                'attributes' => [
                    'label' => 'Admin',
                    'icon' => 'shield',
                ],
                'children' => [
                    [
                        'url' => 'https://example.com/admin/users',
                        'title' => 'Users',
                        'active' => true,
                        'attributes' => [
                            'label' => 'Users',
                            'route' => 'admin.users',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertCount(1, $result);
        $this->assertArrayHasKey('children', $result[0]);
        $this->assertCount(1, $result[0]['children']);
        $this->assertEquals('Users', $result[0]['children'][0]['label']);
        $this->assertTrue($result[0]['children'][0]['active']);
    }

    public function test_sorts_items_by_order_attribute(): void
    {
        $spatieTree = [
            [
                'url' => 'https://example.com/third',
                'title' => 'Third',
                'active' => false,
                'attributes' => [
                    'label' => 'Third',
                    'order' => 20,
                ],
                'children' => [],
            ],
            [
                'url' => 'https://example.com/first',
                'title' => 'First',
                'active' => false,
                'attributes' => [
                    'label' => 'First',
                    'order' => 0,
                ],
                'children' => [],
            ],
            [
                'url' => 'https://example.com/second',
                'title' => 'Second',
                'active' => false,
                'attributes' => [
                    'label' => 'Second',
                    'order' => 10,
                ],
                'children' => [],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertEquals('First', $result[0]['label']);
        $this->assertEquals('Second', $result[1]['label']);
        $this->assertEquals('Third', $result[2]['label']);
    }

    public function test_items_without_order_default_to_end(): void
    {
        $spatieTree = [
            [
                'url' => 'https://example.com/no-order',
                'title' => 'No Order',
                'active' => false,
                'attributes' => [
                    'label' => 'No Order',
                ],
                'children' => [],
            ],
            [
                'url' => 'https://example.com/first',
                'title' => 'First',
                'active' => false,
                'attributes' => [
                    'label' => 'First',
                    'order' => 0,
                ],
                'children' => [],
            ],
        ];

        $result = $this->transformer->transform($spatieTree);

        $this->assertEquals('First', $result[0]['label']);
        $this->assertEquals('No Order', $result[1]['label']);
    }

    public function test_handles_empty_tree(): void
    {
        $result = $this->transformer->transform([]);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
