<?php

namespace App\Services\Navigation;

/**
 * Navigation Transformer Service.
 *
 * Transforms Spatie's navigation tree structure to our MenuItem format
 * for consumption by frontend components.
 */
class NavigationTransformer
{
    /**
     * Transform Spatie navigation tree to MenuItem array.
     *
     * @param  array  $tree  Spatie navigation tree
     * @return array MenuItem array
     */
    public function transform(array $tree): array
    {
        // Sort by order attribute before transforming
        $tree = $this->sortByOrder($tree);

        return array_map(
            fn (array $item) => $this->transformItem($item),
            $tree
        );
    }

    /**
     * Transform a single navigation item.
     *
     * @param  array  $item  Spatie navigation item
     * @return array MenuItem
     */
    private function transformItem(array $item): array
    {
        $attributes = $item['attributes'] ?? [];

        // Build MenuItem structure
        $menuItem = [
            'label' => $attributes['label'] ?? $item['title'],
            'url' => $item['url'] ?? null,
            'active' => $item['active'] ?? false,
        ];

        // Add optional properties from attributes
        if (isset($attributes['route'])) {
            $menuItem['route'] = $attributes['route'];
        }

        if (isset($attributes['icon'])) {
            $menuItem['icon'] = $attributes['icon'];
        }

        if (isset($attributes['action'])) {
            $menuItem['action'] = $attributes['action'];
        }

        if (isset($attributes['type'])) {
            $menuItem['type'] = $attributes['type'];
        }

        // Transform children recursively
        if (! empty($item['children'])) {
            $menuItem['children'] = $this->transform($item['children']);
        }

        return $menuItem;
    }

    /**
     * Sort navigation items by order attribute.
     *
     * @param  array  $items  Navigation items
     * @return array Sorted items
     */
    private function sortByOrder(array $items): array
    {
        usort($items, function ($a, $b) {
            $orderA = $a['attributes']['order'] ?? 999;
            $orderB = $b['attributes']['order'] ?? 999;

            return $orderA <=> $orderB;
        });

        return $items;
    }
}
