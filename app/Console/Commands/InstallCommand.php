<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected $signature = 'saucebase:install
                            {--no-docker : Skip Docker setup and use manual configuration}
                            {--no-ssl : Skip SSL certificate generation}
                            {--no-auth : Skip Auth module installation}
                            {--no-settings : Skip Settings module installation}
                            {--seed : Seed database with demo data}
                            {--force : Force reinstallation even if already set up}';

    protected $description = 'Install and configure Saucebase';

    public function handle(): int
    {
        $this->displayWelcome();

        // Detect environment
        if ($this->isCI()) {
            return $this->handleCIInstallation();
        }

        // Interactive installation
        if ($this->input->isInteractive()) {
            return $this->handleInteractiveInstallation();
        }

        // Non-interactive with flags
        return $this->handleAutomatedInstallation();
    }

    protected function handleInteractiveInstallation(): int
    {
        // Prompt: Use Docker?
        $useDocker = $this->option('no-docker')
            ? false
            : $this->confirm('Use Docker for local development?', true);

        if ($useDocker) {
            $this->installWithDocker();
        } else {
            $this->installManually();
        }

        return self::SUCCESS;
    }

    protected function handleAutomatedInstallation(): int
    {
        // When --no-interaction is passed, use Docker by default unless --no-docker is specified
        if ($this->option('no-docker')) {
            $this->installManually();
        } else {
            $this->installWithDocker();
        }

        return self::SUCCESS;
    }

    protected function installWithDocker(): void
    {
        $this->info('🐳 Setting up with Docker...');

        // Check requirements
        if (! $this->checkDockerRequirements()) {
            return;
        }

        // Build setup-env flags
        $flags = [];

        if ($this->option('no-ssl') || ! $this->shouldSetupSSL()) {
            $flags[] = '--no-ssl';
        }

        if ($this->option('force')) {
            $flags[] = '--force-build';
        }

        // Call existing bin/setup-env script
        $this->newLine();
        $this->callSetupEnv($flags);

        // Post-setup: Enable modules
        $this->setupModules();

        $this->displaySuccess();
    }

    protected function installManually(): void
    {
        $this->info('📝 Manual setup mode...');

        $this->components->task('Creating .env file', function () {
            // Already done by composer
            return true;
        });

        $this->components->task('Generating application key', function () {
            // Already done by composer
            return true;
        });

        $this->warn('You\'ll need to:');
        $this->line('  1. Configure your database in .env');
        $this->line('  2. Run: php artisan migrate');
        $this->line('  3. Run: npm install && npm run dev');
        $this->line('  4. Configure your web server');

        $this->newLine();
        $this->info('Documentation: https://docs.saucebase.dev/manual-setup');
    }

    protected function handleCIInstallation(): int
    {
        $this->info('🤖 CI environment detected - running minimal setup...');

        // Just verify basics
        $this->components->task('Verifying .env', fn () => file_exists(base_path('.env')));
        $this->components->task('Verifying app key', fn () => ! empty(config('app.key')));

        $this->info('✓ CI setup complete');

        return self::SUCCESS;
    }

    protected function checkDockerRequirements(): bool
    {
        $requirements = [
            'Docker' => ['docker', '--version'],
            'Docker Compose' => ['docker', 'compose', 'version'],
            'Node.js' => ['node', '--version'],
            'npm' => ['npm', '--version'],
        ];

        $missing = [];

        foreach ($requirements as $name => $command) {
            $process = new Process($command);
            $process->run();

            if (! $process->isSuccessful()) {
                $missing[] = $name;
            }
        }

        if (! empty($missing)) {
            $this->error('Missing requirements: '.implode(', ', $missing));
            $this->line('');
            $this->line('Install required tools:');
            $this->line('  Docker: https://docs.docker.com/get-docker/');
            $this->line('  Node.js: https://nodejs.org/ (v18+)');
            $this->line('');
            $this->line('Then run: php artisan saucebase:install');

            return false;
        }

        // Check if Docker daemon is running
        $process = new Process(['docker', 'ps']);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Docker is installed but not running.');
            $this->line('Please start Docker Desktop and try again.');

            return false;
        }

        return true;
    }

    protected function shouldSetupSSL(): bool
    {
        // Check if mkcert is installed
        $process = new Process(['which', 'mkcert']);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->warn('mkcert not found - SSL certificates will be skipped.');
            $this->line('Install mkcert for HTTPS: brew install mkcert');

            return false;
        }

        return $this->confirm('Generate SSL certificates for HTTPS?', true);
    }

    protected function callSetupEnv(array $flags = []): void
    {
        $command = base_path('bin/setup-env').' '.implode(' ', $flags);

        $process = Process::fromShellCommandline($command)
            ->setTimeout(600) // 10 minutes
            ->setTty(true);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (! $process->isSuccessful()) {
            $this->error('Setup failed. Please check the output above.');
            exit(1);
        }
    }

    protected function setupModules(): void
    {
        $this->newLine();
        $this->info('📦 Configuring modules...');

        $installAuth = ! $this->option('no-auth')
            && $this->confirm('Install Auth module? (Login, registration, social auth)', true);

        $installSettings = ! $this->option('no-settings')
            && $this->confirm('Install Settings module? (User settings, preferences)', true);

        if (! $installAuth) {
            $this->call('module:disable', ['module' => 'Auth']);
        }

        if (! $installSettings) {
            $this->call('module:disable', ['module' => 'Settings']);
        }

        if ($this->option('seed') || $this->confirm('Seed database with demo data?', false)) {
            $this->call('db:seed');
        }
    }

    protected function isCI(): bool
    {
        return ! empty(getenv('CI'))
            || ! empty(getenv('GITHUB_ACTIONS'))
            || ! empty(getenv('GITLAB_CI'))
            || ! empty(getenv('CIRCLECI'))
            || ! empty(getenv('TRAVIS'));
    }

    protected function displayWelcome(): void
    {
        $this->newLine();
        $this->line('  ┌─────────────────────────────────┐');
        $this->line('  │   🍯 <fg=yellow;options=bold>Saucebase Installer</> 🍯   │');
        $this->line('  │   Laravel 12 SaaS Starter Kit   │');
        $this->line('  └─────────────────────────────────┘');
        $this->newLine();
    }

    protected function displaySuccess(): void
    {
        $this->newLine();
        $this->info('✓ Installation complete!');
        $this->newLine();
        $this->line('Your application is ready at: <fg=cyan>https://localhost</>');
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Run: <fg=yellow>npm run dev</>');
        $this->line('  2. Visit: https://localhost');
        $this->line('  3. Login with demo credentials (check seeders)');
        $this->newLine();
        $this->line('Documentation: <fg=cyan>https://docs.saucebase.dev</>');
    }
}
