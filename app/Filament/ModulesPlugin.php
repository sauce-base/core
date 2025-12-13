<?php

namespace App\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ModulesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'modules';
    }

    public function register(Panel $panel): void
    {
        $useTopNavigation = config('filament.modules.clusters.enabled', false) && config('filament.modules.clusters.use-top-navigation', false);
        $panel->topNavigation($useTopNavigation);

        $plugins = $this->getModulePlugins();

        // Register each module plugin
        foreach ($plugins as $pluginClass) {
            $panel->plugin($pluginClass::make());
        }
    }

    public function boot(Panel $panel): void
    {
        $plugins = $this->getModulePlugins();

        // Collect custom navigation items from module plugins
        $navigationItems = [];
        foreach ($plugins as $pluginClass) {
            $plugin = $pluginClass::make();

            // Check if the plugin has getNavigationItems method
            if (method_exists($plugin, 'getNavigationItems')) {
                $items = $plugin->getNavigationItems();
                if (! empty($items)) {
                    $navigationItems = array_merge($navigationItems, $items);
                }
            }
        }

        // Register collected custom navigation items
        if (! empty($navigationItems)) {
            $panel->navigationItems($navigationItems);
        }
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    protected function getModulePlugins(): array
    {
        $basePath = config('modules.paths.modules', 'modules');
        $appFolder = trim(config('modules.paths.app_folder', 'app'), '/\\');

        $pattern = $basePath.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.
            $appFolder.DIRECTORY_SEPARATOR.'Filament'.DIRECTORY_SEPARATOR.
            '*Plugin.php';

        $pluginPaths = glob($pattern);

        // TODO: simplify this function using existing Modules methods if possible

        return collect($pluginPaths)
            ->map(fn ($path) => $this->convertPathToNamespace($path))
            ->filter(fn ($class) => class_exists($class))
            ->toArray();
    }

    /**
     * Convert a file path to a fully qualified namespace.
     */
    protected function convertPathToNamespace(string $path): string
    {
        $basePath = base_path();
        $relativePath = str_replace($basePath.DIRECTORY_SEPARATOR, '', $path);

        // Remove .php extension
        $relativePath = str_replace('.php', '', $relativePath);

        // Remove '/app/' from the path (modules don't have 'app' in namespace)
        // modules/Auth/app/Filament/AuthPlugin -> modules/Auth/Filament/AuthPlugin
        $relativePath = str_replace(DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $relativePath);

        // Convert directory separators to namespace separators
        // modules/Auth/Filament/AuthPlugin -> modules\Auth\Filament\AuthPlugin
        $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

        // Capitalize 'modules' to 'Modules'
        // modules\Auth\Filament\AuthPlugin -> Modules\Auth\Filament\AuthPlugin
        $namespace = preg_replace('/^modules\\\\/', 'Modules\\', $namespace);

        return $namespace;
    }
}
