# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Backend (PHP/Laravel)
- **Run development server**: `composer dev` - Starts Laravel server, queue worker, logs, and Vite dev server concurrently
- **Run tests**: `composer test` - Runs PHPUnit tests with Pest framework
- **Run single test**: `php artisan test --filter=TestName`
- **Code formatting**: `./vendor/bin/pint` - Laravel Pint for PHP code style
- **Static analysis**: `./vendor/bin/phpstan` - PHPStan via Larastan
- **Database migrations**: `php artisan migrate`
- **Database seeding**: `php artisan db:seed`
- **Clear caches**: `php artisan optimize:clear`

### Frontend (Vue.js/TypeScript)
- **Development server**: `npm run dev` - Vite dev server
- **Build production**: `npm run build` - TypeScript compilation + Vite build
- **Lint JavaScript/Vue**: `npm run lint` - ESLint with auto-fix

### Docker (Laravel Sail)
- **Start containers**: `./vendor/bin/sail up -d`
- **Run artisan commands**: `./vendor/bin/sail artisan [command]`
- **Run npm commands**: `./vendor/bin/sail npm [command]`
- Use Laravel Sail for running all development commands in a containerized environment

## Architecture Overview

This is a Laravel 12 application with Inertia.js and Vue 3 frontend, using the TALL stack pattern with TypeScript.

### Backend Structure
- **Framework**: Laravel 12 with PHP 8.2+
- **Authentication**: Laravel Breeze with Inertia.js
- **Database**: PostgreSQL (configured in docker-compose.yml)
- **Queue**: Redis for background job processing
- **Search**: Typesense for full-text search capabilities
- **Testing**: Pest PHP for feature and unit tests
- **Mail**: Mailpit for local email testing

### Frontend Structure
- **Framework**: Vue 3 with TypeScript
- **Build Tool**: Vite with Laravel plugin
- **Styling**: Tailwind CSS with forms plugin
- **State Management**: Inertia.js for server-driven SPA
- **Routing**: Ziggy for Laravel route helpers in Vue

### Key Patterns
- **Inertia.js Architecture**: Server-side routing with Vue components as pages
- **Shared Props**: User authentication state shared globally via `HandleInertiaRequests` middleware
- **Component Structure**: Reusable Vue components in `resources/js/Components/`
- **Layouts**: `AuthenticatedLayout` and `GuestLayout` for different user states
- **Form Handling**: Laravel form requests with Inertia.js form helpers

### Database & Services
- **PostgreSQL**: Primary database with testing database auto-creation
- **Redis**: Caching and queue backend
- **Typesense**: Search engine service
- **Soketi**: WebSocket server for real-time features
- **Mailpit**: Local mail server for development

### Testing Architecture
- **Pest PHP**: Modern testing framework with Laravel plugin
- **Feature Tests**: Full HTTP request testing in `tests/Feature/`
- **Unit Tests**: Component testing in `tests/Unit/`
- **Database**: Uses `RefreshDatabase` trait for clean test state
- **Authentication Tests**: Comprehensive auth flow testing included

## Important Files
- `app/Http/Middleware/HandleInertiaRequests.php`: Shared props configuration
- `resources/js/app.ts`: Frontend entry point and Inertia setup
- `routes/web.php`: Main application routes
- `vite.config.js`: Asset bundling configuration
- `docker-compose.yml`: Complete development environment setup

## Git Commit Guidelines
- All commits must be one line
- Do not add any author or co-author information to commits
- Group changes by feature, logic, or component - do not commit all changes in one large commit
- Separate infrastructure changes (dependencies, config) from feature changes
- Separate styling changes from functional changes
- Each commit should represent a single logical unit of work