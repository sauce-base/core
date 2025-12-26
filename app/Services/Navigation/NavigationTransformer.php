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
            'active' => $this->calculateActiveState($item, $attributes),
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
     * Calculate the active state for a navigation item.
     *
     * Uses route-based matching for exact active detection instead of URL prefix matching.
     * This prevents parent items from being marked as active when on child routes.
     *
     * @param  array  $item  Spatie navigation item
     * @param  array  $attributes  Item attributes
     * @return bool Whether the item is active
     */
    private function calculateActiveState(array $item, array $attributes): bool
    {
        // If a route attribute is provided, use route-based matching
        if (isset($attributes['route'])) {
            try {
                return request()->routeIs($attributes['route']);
            } catch (\Exception) {
                // Route doesn't exist or error occurred
                return false;
            }
        }

        // Fallback to Spatie's URL-based active detection only if no route is specified
        // Note: This uses prefix matching, so it may mark parent paths as active
        return $item['active'] ?? false;
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
