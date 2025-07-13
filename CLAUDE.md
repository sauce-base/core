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

### E2E Testing (Playwright)
- **Run all e2e tests**: `npm run test:e2e` - Runs tests in headless mode
- **Run with UI**: `npm run test:e2e:ui` - Opens Playwright UI for interactive testing
- **Run in headed mode**: `npm run test:e2e:headed` - Runs tests with browser visible
- **Debug tests**: `npm run test:e2e:debug` - Runs tests in debug mode
- **View test report**: `npm run test:e2e:report` - Opens HTML test report

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

## E2E Testing Guidelines

### Test Structure
- **Tests location**: `tests/e2e/` directory
- **Page Object Models**: `tests/e2e/pages/` - Encapsulate page interactions
- **Test utilities**: `tests/e2e/utils/` - Shared helper functions
- **Test fixtures**: `tests/e2e/fixtures/` - Test data and mock objects

### Writing Tests
- Use Page Object Model pattern for maintainable tests
- Prefer `data-testid` attributes over CSS selectors for element targeting
- Use descriptive test names that explain the expected behavior
- Group related tests using `test.describe()` blocks
- Use `test.beforeEach()` for common setup operations

### Best Practices
- **Element Selection Priority**:
  1. `getByTestId()` - Most reliable for custom elements
  2. `getByRole()` - Best for semantic elements (buttons, links)
  3. `getByLabel()` - Good for form controls
  4. `getByPlaceholder()` - Fallback for inputs
- **Assertions**: Use web-first assertions that auto-wait (e.g., `toBeVisible()`)
- **Test Data**: Store test data in fixtures rather than hardcoding in tests
- **Authentication**: Use helper functions for login flows in `utils/auth.ts`

### Data-testid Conventions
- Form fields: Use field name (e.g., `data-testid="email"`)
- Error messages: Use `{field-name}-error` (e.g., `data-testid="email-error"`)
- Interactive elements: Use descriptive names (e.g., `data-testid="password-toggle"`)

### Running Tests
- **Start development servers first**: Run `composer dev` in a separate terminal
- This starts both Laravel (port 8000) and Vite (port 5173) servers concurrently
- **Run tests**: `npm run test:e2e` (expects servers to be running)
- **Development workflow**:
  1. Terminal 1: `composer dev` (keep running)
  2. Terminal 2: `npm run test:e2e:ui` for interactive testing
- **Debug tests**: Use headed mode (`npm run test:e2e:headed`) for visual debugging
- **CI mode**: Tests will automatically start servers if `CI=true` environment variable is set

## Git Commit Guidelines
- All commits must be one line
- Do not add any author or co-author information to commits
- Group changes by scope and logical relationship:
  - Changes that are part of the same feature/scope should be committed together
  - Infrastructure changes (dependencies, config files) should be separate from feature implementation
  - Component creation and its immediate usage should be in the same commit when they form a complete feature
  - Styling/formatting changes should be separate from functional changes
  - Bug fixes should be isolated from new features
  - Documentation updates should be separate unless directly related to code changes in the same commit
- Each commit should represent a single, complete, logical unit of work that could be deployed independently

## Test Guidelines
- **Test Execution Strategy**:
  - Run the test using a command which you can get the results, don't run using the ui mode as you can't get any results from it