<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * Note: Core routes/breadcrumbs.php is automatically loaded by the package.
     * This provider only loads module-specific breadcrumb files.
     */
    public function boot(): void
    {
        // Load module breadcrumbs
        $this->loadModuleBreadcrumbs();
    }

    /**
     * Load breadcrumbs from a file.
     */
    protected function loadBreadcrumbsFrom(string $path): void
    {
        if (file_exists($path)) {
            require $path;
        }
    }

    /**
     * Load breadcrumbs from enabled modules.
     */
    protected function loadModuleBreadcrumbs(): void
    {
        $modules = Module::allEnabled();

        foreach ($modules as $module) {
            $breadcrumbsPath = $module->getPath().'/routes/breadcrumbs.php';
            $this->loadBreadcrumbsFrom($breadcrumbsPath);
        }
    }
}
