<?php

namespace App\Console\Commands\SauceBase;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkModuleCommand extends Command
{
    protected $signature = 'saucebase:module:link {path : Path to the external module directory}';

    protected $description = 'Link an external module for local development';

    public function getHelp(): string
    {
        return <<<'HELP'
                Link an external module directory to the project for local development.

                This command creates a symlink, updates composer.json repositories, and adds
                the symlink to .gitignore automatically.

                <comment>Usage:</comment>
                php artisan module:link ../modules/auth
                php artisan module:link /path/to/modules/dashboard

                <comment>After linking, install the module:</comment>
                composer require saucebase/auth@dev

                <comment>Note:</comment>
                - Module name is auto-detected from the directory name
                - Only works in local environment
                - Safe to run multiple times
                HELP;
    }

    public function handle()
    {
        if (! app()->environment('local')) {
            $this->error('This command can only be run in local environment.');

            return Command::FAILURE;
        }

        $modulePath = $this->argument('path');
        $moduleNameStudly = basename($modulePath);
        $moduleNameKebab = str($moduleNameStudly)->kebab()->toString();
        $packageName = "saucebase/{$moduleNameKebab}";

        if (! $this->validateModulePath($modulePath)) {
            return Command::FAILURE;
        }

        if (! $this->createSymlink($modulePath, $moduleNameStudly)) {
            return Command::FAILURE;
        }

        $this->addToComposerJson($moduleNameStudly);
        $this->addToGitignore($moduleNameStudly);

        $this->newLine();
        $this->info('🎉 Module linked successfully!');
        $this->newLine();
        $this->info('To install the module, run:');
        $this->line("  <fg=green>composer require {$packageName}@dev</>");

        return Command::SUCCESS;
    }

    private function validateModulePath(string $modulePath): bool
    {
        if (! File::exists($modulePath)) {
            $this->error("Module path does not exist: {$modulePath}");

            return false;
        }

        if (! File::exists("{$modulePath}/composer.json")) {
            $this->error("composer.json not found in module path: {$modulePath}");

            return false;
        }

        return true;
    }

    private function createSymlink(string $modulePath, string $moduleNameStudly): bool
    {
        $absoluteModulePath = realpath($modulePath);
        $relativeModulePath = $this->getRelativePath(base_path('modules'), $absoluteModulePath);
        $symlinkPath = base_path("modules/{$moduleNameStudly}");

        if (File::exists($symlinkPath)) {
            if (is_link($symlinkPath) && readlink($symlinkPath) === $relativeModulePath) {
                $this->info('Symlink already exists and points to the correct location.');

                return true;
            }

            $this->error("Path already exists: modules/{$moduleNameStudly}");

            return false;
        }

        symlink($relativeModulePath, $symlinkPath);
        $this->info("✅ Created symlink: modules/{$moduleNameStudly} -> {$relativeModulePath}");

        return true;
    }

    private function addToComposerJson(string $moduleNameStudly): void
    {
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(File::get($composerJsonPath), true);
        $repositoryPath = "./modules/{$moduleNameStudly}";

        foreach ($composerJson['repositories'] ?? [] as $repo) {
            if (($repo['url'] ?? '') === $repositoryPath) {
                $this->info('Repository already exists in composer.json');

                return;
            }
        }

        $composerJson['repositories'][] = [
            'type' => 'path',
            'url' => $repositoryPath,
            'options' => [
                'symlink' => true,
            ],
        ];

        File::put(
            $composerJsonPath,
            json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
        );

        $this->info('✅ Added repository to composer.json');
    }

    private function addToGitignore(string $moduleNameStudly): void
    {
        $gitignorePath = base_path('.gitignore');

        if (! File::exists($gitignorePath)) {
            return;
        }

        $gitignoreContent = File::get($gitignorePath);
        $gitignoreEntry = "modules/{$moduleNameStudly}";

        if (str_contains($gitignoreContent, $gitignoreEntry)) {
            $this->info('Already exists in .gitignore');

            return;
        }

        if (str_contains($gitignoreContent, '# Module symlinks')) {
            $gitignoreContent = preg_replace(
                '/(# Module symlinks.*?\n)/',
                "$1{$gitignoreEntry}\n",
                $gitignoreContent
            );
        } else {
            $gitignoreContent .= "\n# Module symlinks (generated from external modules)\n{$gitignoreEntry}\n";
        }

        File::put($gitignorePath, $gitignoreContent);
        $this->info('✅ Added to .gitignore');
    }

    private function getRelativePath(string $from, string $to): string
    {
        $from = explode('/', str_replace('\\', '/', $from));
        $to = explode('/', str_replace('\\', '/', $to));

        $relPath = $to;

        foreach ($from as $depth => $dir) {
            if (isset($to[$depth]) && $dir === $to[$depth]) {
                array_shift($relPath);
            } else {
                $remaining = count($from) - $depth;
                if ($remaining > 0) {
                    $relPath = array_pad($relPath, -(count($relPath) + $remaining), '..');
                    break;
                }
            }
        }

        return implode('/', $relPath);
    }
}
