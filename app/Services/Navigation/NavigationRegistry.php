<?php

namespace App\Services\Navigation;

use Closure;
use Spatie\Navigation\Navigation;

/**
 * Navigation Registry Service.
 *
 * Central registry providing three Navigation instances (app, settings, user)
 * and helper methods for modules to register navigation items.
 */
// TODO: refactor to use a single Navigation instance with groups
class NavigationRegistry
{
    /**
     * Registered callbacks for each navigation group.
     */
    private array $appCallbacks = [];

    private array $settingsCallbacks = [];

    private array $userCallbacks = [];

    private array $secondaryCallbacks = [];

    /**
     * Get the app navigation instance.
     */
    public function app(): NavigationBuilder
    {
        return new NavigationBuilder($this->appCallbacks);
    }

    /**
     * Get the secondary navigation instance.
     */
    public function secondary(): NavigationBuilder
    {
        return new NavigationBuilder($this->secondaryCallbacks);
    }

    /**
     * Get the settings navigation instance.
     */
    public function settings(): NavigationBuilder
    {
        return new NavigationBuilder($this->settingsCallbacks);
    }

    /**
     * Get the user menu navigation instance.
     */
    public function user(): NavigationBuilder
    {
        return new NavigationBuilder($this->userCallbacks);
    }
}

/**
 * Navigation Builder - fluent API wrapper for a navigation group.
 */
class NavigationBuilder
{
    private array $callbacks;

    public function __construct(array &$callbacks)
    {
        $this->callbacks = &$callbacks;
    }

    public function add(string $title, string $url, ?Closure $callback = null): self
    {
        $this->callbacks[] = ['title' => $title, 'url' => $url, 'callback' => $callback];

        return $this;
    }

    public function addIf(bool $condition, string $title, string $url, ?Closure $callback = null): self
    {
        if ($condition) {
            $this->add($title, $url, $callback);
        }

        return $this;
    }

    public function tree(): array
    {
        // Create a fresh Navigation instance to avoid sharing state between groups
        $activeUrlChecker = app(\Spatie\Navigation\Helpers\ActiveUrlChecker::class);
        $navigation = new Navigation($activeUrlChecker);

        foreach ($this->callbacks as $item) {
            $navigation->add($item['title'], $item['url'], $item['callback']);
        }

        return $navigation->tree();
    }

    public function breadcrumbs(): array
    {
        // Create a fresh Navigation instance to avoid sharing state between groups
        $activeUrlChecker = app(\Spatie\Navigation\Helpers\ActiveUrlChecker::class);
        $navigation = new Navigation($activeUrlChecker);

        foreach ($this->callbacks as $item) {
            $navigation->add($item['title'], $item['url'], $item['callback']);
        }

        return $navigation->breadcrumbs();
    }
}
