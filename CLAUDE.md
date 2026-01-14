# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Saucebase is a modular Laravel SaaS starter kit built on the VILT stack (Vue 3, Inertia.js, Laravel 12, Tailwind CSS 4). It follows a **copy-and-own philosophy** where modules are installed directly into the repository rather than being maintained as external packages. This is a Docker-first setup with hot reload, TypeScript, and built-in best practices.

**Key Technologies:**

- Backend: Laravel 12, PHP 8.4+, Filament 4 admin panel
- Frontend: Vue 3 Composition API, TypeScript 5.8, Inertia.js 2.0, Tailwind CSS 4
- Build: Vite 6.4 with HMR, SSR support
- Testing: PHPUnit (backend), Playwright (E2E)
- Code Quality: PHPStan level 9, Laravel Pint, ESLint, Prettier
- Infrastructure: Docker (Nginx, MySQL 8, Redis, Mailpit)

## Common Commands

### Development

```bash
# Start development environment (recommended)
composer dev
# Runs: Laravel dev server, queue worker, Pail logs, and Vite dev server in parallel

# Alternative: Individual services
php artisan serve                    # Start Laravel dev server
npm run dev                          # Start Vite dev server with HMR
php artisan queue:listen --tries=1   # Start queue worker
php artisan pail --timeout=0         # Monitor logs in real-time
```

### Docker Operations

```bash
# Start all services
docker compose up -d --wait

# Execute commands in app container
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app composer install

# Restart services
docker compose restart app

# View logs
docker compose logs -f app
docker compose logs -f nginx
```

### Installation & Setup

```bash
# Quick install (recommended for new projects)
php artisan saucebase:install

# Advanced options
php artisan saucebase:install --no-docker    # Skip Docker setup
php artisan saucebase:install --no-ssl       # Skip SSL generation
php artisan saucebase:install --force        # Force reinstallation
php artisan saucebase:install --no-interaction  # CI/CD mode
```

### Module Management

**Important**: Modules are Composer packages that get installed directly into your repository. They are not external dependencies but code you own and can customize.

#### Available Official Modules

| Module       | Description                                               | Repository                                                    |
| ------------ | --------------------------------------------------------- | ------------------------------------------------------------- |
| **Auth**     | Authentication with social login support (Google, GitHub) | [sauce-base/auth](https://github.com/sauce-base/auth)         |
| **Settings** | Settings management module                                | [sauce-base/settings](https://github.com/sauce-base/settings) |

#### Installation Steps

To install a module, follow these steps in order:

```bash
# 1. Install the module via Composer
composer require saucebase/auth

# For development-only modules, use --dev flag
# composer require --dev saucebase/module-name

# 2. Regenerate autoload files
composer dump-autoload

# 3. Enable the module
docker compose exec app php artisan module:enable Auth

# 4. Run migrations and seeders
docker compose exec app php artisan module:migrate Auth --seed

# 5. Build frontend assets to include module resources
npm run build
```

**What each command does:**

1. `composer require` - Downloads the module package and adds it to composer.json
2. `composer dump-autoload` - Regenerates Composer's autoload files to include new module classes
3. `module:enable` - Marks the module as enabled in `modules_statuses.json`
4. `module:migrate --seed` - Runs database migrations and seeds module data
5. `npm run build` - Rebuilds frontend assets to include module JavaScript/CSS

**Alternative for local development (without Docker):**

```bash
composer require saucebase/auth
composer dump-autoload
php artisan module:enable Auth
php artisan module:migrate Auth --seed
npm run build
```

#### Managing Installed Modules

```bash
# Enable/disable modules
php artisan module:enable Auth
php artisan module:disable Auth

# Run module-specific operations
php artisan module:migrate Auth         # Run module migrations
php artisan module:migrate-refresh Auth # Refresh module migrations
php artisan module:seed Auth            # Seed module data
php artisan module:list                 # List all modules

# Inside Docker
docker compose exec app php artisan module:list
```

**Important:** After enabling/disabling modules, rebuild frontend assets with `npm run build` or restart `npm run dev`.

#### Example: Installing Auth Module

```bash
# Install the package
composer require saucebase/auth
composer dump-autoload

# Enable and migrate
docker compose exec app php artisan module:enable Auth
docker compose exec app php artisan module:migrate Auth --seed

# Build assets
npm run build

# Configure OAuth (optional)
# Add to .env:
# GOOGLE_CLIENT_ID=your-client-id
# GOOGLE_CLIENT_SECRET=your-client-secret
# GITHUB_CLIENT_ID=your-client-id
# GITHUB_CLIENT_SECRET=your-client-secret
```

**The Auth module provides:**

- Login, registration, password reset flows
- OAuth integration (Google, GitHub via Laravel Socialite)
- Multiple provider connections per user
- Routes: `/auth/login`, `/auth/register`, `/auth/forgot-password`
- Admin panel access at `/admin` (credentials: `chef@saucebase.dev` / `secretsauce`)

### Testing

```bash
# Backend tests (PHPUnit)
php artisan test                    # Run all tests
php artisan test --filter TestName  # Run specific test
php artisan test tests/Feature      # Run feature tests only
php artisan test tests/Unit         # Run unit tests only
php artisan test --testsuite=Modules  # Run module tests only

# E2E tests (Playwright)
npm run test:e2e                   # Run all E2E tests
npm run test:e2e:ui                # Open Playwright UI
npm run test:e2e:headed            # Run tests in headed mode
npm run test:e2e:debug             # Debug tests
npm run test:e2e:report            # View test report
```

### Code Quality

```bash
# PHP
composer analyse                   # Run PHPStan analysis
composer lint                      # Run Laravel Pint formatter
vendor/bin/pint                    # Format PHP code
vendor/bin/phpstan analyse --memory-limit=2G  # Static analysis

# JavaScript/TypeScript
npm run lint                       # Run ESLint with auto-fix
npm run format                     # Format with Prettier
npm run format:check               # Check formatting
```

### Asset Building

```bash
# Development
npm run dev                        # Start Vite dev server with HMR

# Production
npm run build                      # Build for production (includes SSR)
npm run build:ssr                  # Explicitly build with SSR
npm run preview                    # Preview production build
```

### Database

```bash
# Migrations
php artisan migrate                      # Run migrations
php artisan migrate:fresh --seed         # Fresh migration with seeding
php artisan migrate:status               # Check migration status
php artisan migrate:rollback             # Rollback last migration

# Seeders
php artisan db:seed                      # Run DatabaseSeeder
php artisan db:seed --class=RolesDatabaseSeeder  # Run specific seeder
```

### Cache & Optimization

```bash
# Clear caches
php artisan optimize:clear         # Clear all caches
php artisan config:clear           # Clear config cache
php artisan route:clear            # Clear route cache
php artisan view:clear             # Clear view cache

# Optimize for production
php artisan optimize               # Cache config, routes, views
php artisan config:cache           # Cache configuration
php artisan route:cache            # Cache routes
php artisan view:cache             # Compile views
```

## Architecture

### Modular Structure

Saucebase uses **nwidart/laravel-modules** for module management. Modules are self-contained feature packs that can be installed, enabled, or disabled independently.

```
modules/
├── <ModuleName>/
│   ├── app/                    # Module backend code
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   ├── Providers/
│   │   └── ...
│   ├── config/                 # Module configuration
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/
│   │   └── factories/
│   ├── lang/                   # Module translations
│   ├── resources/
│   │   ├── js/                # Vue components, pages
│   │   │   ├── app.ts         # Module setup (optional)
│   │   │   ├── pages/         # Inertia pages
│   │   │   └── components/    # Vue components
│   │   └── css/               # Module styles
│   ├── routes/
│   │   ├── web.php
│   │   └── api.php
│   ├── tests/
│   │   ├── Feature/
│   │   ├── Unit/
│   │   └── e2e/               # Playwright tests
│   ├── vite.config.js         # Module asset paths
│   ├── playwright.config.ts   # Module E2E config (optional)
│   └── module.json            # Module metadata
```

**Module Discovery:**

- Modules are tracked in `modules_statuses.json` (format: `{"ModuleName": true}`)
- Only enabled modules are loaded and built
- The `module-loader.js` automatically discovers and collects enabled module assets, translations, and Playwright configs

### Frontend Architecture

**Inertia SPA with Module Support:**

The frontend uses a custom module resolution system that allows pages to be loaded from modules using namespace syntax:

```typescript
// In routes: render module pages like this
return inertia('Auth::Login', ['data' => $data]);

// Resolves to: modules/Auth/resources/js/pages/Login.vue
```

**Key Frontend Files:**

- `resources/js/app.ts` - Main Inertia app entry point
- `resources/js/ssr.ts` - SSR entry point
- `resources/js/lib/utils.ts` - Contains `resolveModularPageComponent()` for module page resolution
- `resources/js/lib/moduleSetup.ts` - Module lifecycle management (setup, afterMount)

**Module Lifecycle:**

Modules can export setup hooks in `modules/<Name>/resources/js/app.ts`:

```typescript
export default {
    setup(app) {
        // Called before Vue app mounts
        // Register plugins, components, etc.
    },
    afterMount(app) {
        // Called after Vue app mounts
        // Initialize services that require DOM
    },
};
```

**Component Library:**

Uses shadcn-vue style components in `resources/js/components/ui/` with Tailwind CSS 4. Components follow the copy-and-own pattern and can be customized directly.

### Backend Architecture

**Service Providers:**

- `AppServiceProvider` - Core app configuration, HTTPS enforcement, fixes module event discovery
- `MacroServiceProvider` - Centralized location for all application macros (e.g., Inertia `->withSSR()`)
- `ModuleServiceProvider` (abstract) - Base class for module service providers, handles translations, config, migrations, and Inertia data sharing
- `NavigationServiceProvider` - Spatie navigation configuration
- `BreadcrumbServiceProvider` - Diglactic breadcrumbs setup
- `FilamentServiceProvider` - Filament admin panel configuration

**Module Service Providers:**

All modules must extend `App\Providers\ModuleServiceProvider` and define `$name` and `$nameLower` properties:

```php
class AuthServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Auth';
    protected string $nameLower = 'auth';

    protected array $commands = [
        // Command classes
    ];

    protected array $providers = [
        // Additional service providers
    ];
}
```

**Helpers:**

Global helper functions are auto-loaded from `app/Helpers/helpers.php` (currently empty, but available for project-wide utilities).

### Asset Pipeline

**Vite Configuration (`vite.config.js`):**

- Dynamically loads SSL certificates if present (`docker/ssl/app.pem`, `docker/ssl/app.key.pem`)
- Uses `module-loader.js` to collect language paths from enabled modules
- Provides path aliases: `@` → `resources/js`, `@modules` → `modules/`, `ziggy-js` → vendor path

**Module Asset Collection:**

The `module-loader.js` provides functions to collect module resources:

- `collectModuleAssetsPaths()` - Collect asset paths from module `vite.config.js` files
- `collectModuleLangPaths()` - Collect translation directories
- `collectModulePlaywrightConfigs()` - Collect E2E test projects

Each module can export a `vite.config.js` with a `paths` array:

```javascript
export default {
    paths: ['css/app.css', 'js/app.ts'], // Relative to modules/<Name>/resources/
};
```

### Multi-Tenancy Support

SSL certificates are generated with wildcard support (`*.localhost`) to enable multi-tenant applications. The infrastructure is ready for packages like Spatie Laravel Multitenancy or Tenancy for Laravel.

**Supported domains:**

- `https://localhost` - Main application
- `https://*.localhost` - Any subdomain works with the wildcard certificate

### Environment Configuration

**Saucebase-specific variables:**

- `APP_HOST` - Application hostname (default: `localhost`)
- `APP_URL` - Full URL, must match APP_HOST (default: `https://localhost`)
- `APP_SLUG` - Project slug for storage/database keys (default: `saucebase`)
- `VITE_LOCAL_STORAGE_KEY` - Frontend storage prefix (default: `${APP_SLUG}`)

**HTTPS Configuration:**

The `AppServiceProvider` automatically enforces HTTPS in production/staging and respects local SSL setup. SSL enforcement includes:

- URL generation forced to HTTPS
- Security headers (HSTS, CSP upgrade-insecure-requests, X-Content-Type-Options)

### Testing Architecture

**PHPUnit (`phpunit.xml`):**

Three test suites configured:

- `Unit` - `tests/Unit/`
- `Feature` - `tests/Feature/`
- `Modules` - `modules/*/tests/Feature/` and `modules/*/tests/Unit/`

Tests run with SQLite in-memory database by default.

**Playwright (`playwright.config.ts`):**

- Automatically discovers E2E tests from enabled modules via `module-loader.js`
- Creates test projects for each module prefixed with `@ModuleName`
- Core tests in `tests/e2e/` run as `@Core`
- Each project runs across selected devices (default: Desktop Chrome)
- Vite dev server started automatically in local environment (not CI)

## Git Workflow & Commit Standards

### Commit Message Format

This project enforces strict commit message standards using Commitlint with Husky hooks.

**Format:**

```
type(scope): subject
```

or

```
type: subject
```

**Rules:**

- **Single-line commits only** - No body or footer allowed
- Maximum header length: 150 characters
- Type: required, must be lowercase
- Scope: optional, must be lowercase
- Subject: required, must be lowercase (cannot start with capital letter)

### Allowed Commit Types

| Type       | Description                                               | Example                                         |
| ---------- | --------------------------------------------------------- | ----------------------------------------------- |
| `feat`     | A new feature                                             | `feat(auth): add social login support`          |
| `fix`      | A bug fix                                                 | `fix(dashboard): resolve chart rendering issue` |
| `docs`     | Documentation only changes                                | `docs: update installation guide`               |
| `style`    | Code style changes (formatting, missing semicolons, etc.) | `style: format components with prettier`        |
| `refactor` | Code changes that neither fix bugs nor add features       | `refactor(api): simplify error handling logic`  |
| `perf`     | Performance improvements                                  | `perf(queries): optimize database queries`      |
| `test`     | Adding or correcting tests                                | `test(auth): add login validation tests`        |
| `chore`    | Build process or tooling changes                          | `chore: update dependencies`                    |
| `ci`       | CI configuration changes                                  | `ci: add playwright workflow`                   |
| `build`    | Build system or external dependency changes               | `build: upgrade vite to 6.4`                    |
| `revert`   | Reverts a previous commit                                 | `revert: revert feat(auth): add social login`   |

### Commit Examples

**Valid commits:**

```bash
feat: add user authentication module
fix(api): resolve timeout issue in user endpoint
docs: update readme with docker instructions
refactor: simplify module loader logic
test(e2e): add playwright tests for login flow
chore(deps): upgrade laravel to 12.0
```

**Invalid commits (will be rejected):**

```bash
# ❌ Type must be lowercase
Feat: add new feature

# ❌ Subject cannot start with capital letter
feat: Add new feature

# ❌ Invalid type
feature: add new feature

# ❌ Type cannot be empty
add new feature

# ❌ Body/footer not allowed (single-line only)
feat: add new feature

This adds a new feature for users
```

### Pre-commit Hooks

Husky automatically runs these checks before each commit:

**PHP Formatting:**

- `composer lint` - Runs Laravel Pint to auto-format PHP code

**JavaScript/TypeScript/Vue Formatting:**

- `npx lint-staged` - Runs ESLint and Prettier on staged files
- Affected files: `**/*.{js,ts,vue}`
- Auto-fixes and formats code before commit

**Commit Message Validation:**

- `commitlint` - Validates commit message format (runs on commit-msg hook)

### Manual Validation

```bash
# Test commit message format
echo "feat: test commit" | npx commitlint

# Run linters manually
composer lint          # PHP
npm run lint          # JavaScript/TypeScript
npm run format        # Prettier
```

## Development Principles & Coding Standards

### Core Principles

This project follows industry best practices for clean, maintainable code:

**DRY (Don't Repeat Yourself)**

- **CRITICAL**: Extract common logic into reusable functions, classes, or composables
- Abstract when the same logic appears 3+ times
- Apply DRY to tests: Create helper functions for repeated test logic
- Examples:
    - Use composables for shared Vue logic
    - Service classes for shared backend logic
    - Test helpers for repeated assertions (e.g., `tests/e2e/helpers/ssr.ts`)

**KISS (Keep It Simple, Stupid)**

- Prefer simple, obvious solutions over clever ones
- Write code that others can understand at a glance
- Avoid premature optimization

**YAGNI (You Aren't Gonna Need It)**

- Don't build features for hypothetical future requirements
- Implement only what's needed now
- Refactor when requirements actually change

**Single Responsibility Principle**

- Each class/function should do one thing well
- Controllers handle HTTP requests, services contain business logic
- Components focus on presentation, composables manage state/logic

**Separation of Concerns**

- Backend: Controllers → Services → Models
- Frontend: Pages → Components → Composables → Utils

### Planning & Implementation Philosophy

When planning and implementing features, always follow these guidelines:

**Minimum Viable Implementation (MVI)**

- Start with the **simplest solution that solves the problem**
- Avoid building for hypothetical future needs (see YAGNI above)
- Deliver working functionality first, then iterate if needed
- Ask: "What's the minimum code needed to solve this problem?"

**Simplicity Over Complexity**

- Prefer 5 lines of clear code over 50 lines of "clever" abstraction
- If you can solve it with a macro, don't build a middleware + gateway + config system
- Avoid patterns like Strategy, Factory, Builder unless truly needed
- Example: A simple `Config::set()` beats a custom Gateway with interfaces

**User Experience First**

- Developer experience is user experience
- Code should be discoverable and self-documenting
- Explicit is better than implicit (e.g., `->withSSR()` vs hidden config)
- IDE autocomplete and type hints matter
- Documentation should show actual usage, not theory

**When Creating Plans**

- Plans must focus on **minimum viable implementation**
- Always ask: "Can this be simpler?"
- Prefer fewer files over more files
- Prefer fewer abstractions over more abstractions
- Question every new class, interface, or pattern
- If a feature can be implemented in 5 lines vs 50 lines, choose 5 lines
- Example: A 5-line macro is better than middleware + config + gateway

**Red Flags in Plans (Avoid These)**

- ❌ Multiple new files when one edit would work
- ❌ New interfaces/abstractions for single implementations
- ❌ "Extensibility" or "flexibility" without concrete requirements
- ❌ Patterns (Factory, Strategy, etc.) for simple tasks
- ❌ Over-engineered solutions that are hard to understand

**Good Plan Indicators**

- ✅ Minimal file changes (prefer editing over creating)
- ✅ Clear, explicit code that's easy to understand
- ✅ Developer-friendly API (chainable, discoverable)
- ✅ Solves the actual problem without extra "features"
- ✅ Can be explained in one sentence

### Code Quality Standards

#### PHP/Laravel Standards

**Enforced by Tools:**

- PHPStan level 5 static analysis (run: `composer analyse`)
- Laravel Pint PSR-12 formatting (run: `composer lint`)
- Pre-commit hooks ensure compliance

**Required Practices:**

```php
// ✅ Good: Type hints, PHPDoc, clear method names
/**
 * Retrieve active users with their roles.
 *
 * @return \Illuminate\Database\Eloquent\Collection<int, User>
 */
public function getActiveUsers(): Collection
{
    return User::with('roles')
        ->where('active', true)
        ->get();
}

// ❌ Bad: No types, unclear name, missing docs
public function getUsers()
{
    return User::where('active', true)->get();
}
```

**Class Structure:**

- Max 200-300 lines per class (if larger, consider splitting)
- Max 20-30 lines per method
- Use service classes for complex business logic
- Keep controllers thin (validate input, call service, return response)

#### JavaScript/TypeScript Standards

**Enforced by Tools:**

- ESLint with Vue + TypeScript rules (run: `npm run lint`)
- Prettier formatting (run: `npm run format`)
- Pre-commit hooks ensure compliance

**Required Practices:**

```typescript
// ✅ Good: TypeScript types, composables, clear structure
<script setup lang="ts">
import { ref, computed } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
}

const props = defineProps<{
    users: User[];
}>();

const activeUsers = computed(() =>
    props.users.filter(u => u.active)
);
</script>

// ❌ Bad: No types, unclear logic
<script setup>
const props = defineProps(['users']);
const filtered = props.users.filter(u => u.active);
</script>
```

### When to Abstract vs When to Keep Simple

**✅ Create Abstractions When:**

- Same logic appears **3+ times**
- Clear reusability across multiple contexts
- Well-defined interface/contract
- Logic is complex enough to warrant isolation

**Examples:**

```php
// ✅ Good abstraction: Reusable service
class UserNotificationService {
    public function notifyPasswordChanged(User $user): void
    {
        $user->notify(new PasswordChangedNotification());
    }
}

// ✅ Good abstraction: Composable for shared state
// useLocalization.ts
export function useLocalization() {
    const language = ref(loadStoredLanguage());
    return { language, setLanguage };
}
```

**❌ Don't Create Abstractions When:**

- Logic used only **once** or **twice**
- Abstraction makes code harder to understand
- Building for hypothetical future needs
- Simple inline code is clearer

**Examples:**

```php
// ❌ Bad: Over-engineered for one-time use
class StringUppercaseTransformer {
    public function transform(string $input): string {
        return strtoupper($input);
    }
}

// ✅ Good: Simple inline operation
$name = strtoupper($user->name);
```

### Security Best Practices

**OWASP Top 10 Awareness:**

1. **SQL Injection Prevention**
    - ✅ Use Eloquent ORM or query builder
    - ✅ Use parameter binding
    - ❌ Never concatenate user input into queries

2. **XSS Prevention**
    - ✅ Vue automatically escapes template output
    - ✅ Use `v-html` only with sanitized content
    - ❌ Never trust user input in HTML

3. **CSRF Protection**
    - ✅ Laravel CSRF middleware enabled by default
    - ✅ `@csrf` directive in forms

4. **Command Injection**
    - ❌ Avoid `exec()`, `shell_exec()`, `system()` with user input
    - ✅ Use Laravel's process handling if needed

5. **Authentication/Authorization**
    - ✅ Use Laravel's built-in auth system
    - ✅ Check permissions with gates/policies
    - ✅ Validate ownership before modifying resources

6. **Sensitive Data**
    - ❌ Never commit `.env` files or API keys
    - ✅ Use environment variables
    - ✅ Add secrets to `.gitignore`

### Readability & Maintainability

**Naming Conventions:**

```php
// ✅ Good: Descriptive, self-documenting
$activeSubscriptionUsers = User::whereHas('subscription', fn($q) =>
    $q->where('status', 'active')
)->get();

// ❌ Bad: Unclear abbreviations
$asUsers = User::whereHas('sub', fn($q) => $q->where('s', 'a'))->get();
```

**Function Length:**

- Ideal: 10-20 lines
- Maximum: 30-40 lines
- If longer, break into smaller functions

**Nesting Depth:**

- Maximum: 3-4 levels deep
- Use early returns to reduce nesting

```php
// ✅ Good: Early returns, flat structure
public function process(User $user): bool
{
    if (!$user->isActive()) {
        return false;
    }

    if (!$user->hasPermission('process')) {
        return false;
    }

    return $this->performProcess($user);
}

// ❌ Bad: Deep nesting
public function process(User $user): bool
{
    if ($user->isActive()) {
        if ($user->hasPermission('process')) {
            return $this->performProcess($user);
        }
    }
    return false;
}
```

### Testing Standards

**Required Tests:**

- Feature tests for user-facing workflows
- Unit tests for complex business logic
- E2E tests for critical user paths (auth, checkout, etc.)

**Test Organization:**

```php
// ✅ Good: Clear test structure
/** @test */
public function it_creates_user_with_valid_data(): void
{
    // Arrange
    $data = ['name' => 'John', 'email' => 'john@example.com'];

    // Act
    $user = User::create($data);

    // Assert
    $this->assertDatabaseHas('users', $data);
}
```

**Coverage Expectations:**

- Critical business logic: 80%+ coverage
- Service classes: 70%+ coverage
- Controllers: Feature tests over unit tests

**DRY Principle in Tests:**

- **ALWAYS** extract repeated test logic into helper functions
- Create helper files in `tests/Feature/helpers/` or `tests/e2e/helpers/`
- Abstract when the same test logic appears 2+ times (lower threshold than production code)
- Examples:
    - E2E SSR helpers: `tests/e2e/helpers/ssr.ts` (expectSSREnabled, expectSSRDisabled)
    - Authentication helpers (login, logout, register flows)
    - Database seeding helpers (createUser, createProduct)

```typescript
// ✅ Good: Reusable test helpers
// tests/e2e/helpers/ssr.ts
export async function expectSSREnabled(page: Page, expectedComponent?: string) {
    const htmlContent = await page.content();
    expect(htmlContent).toContain('id="app"');
    expect(htmlContent).toContain('data-page');
    // ... more assertions
}

// tests/e2e/index.spec.ts
test('uses SSR', async ({ page }) => {
    await page.goto('/');
    await expectSSREnabled(page, 'Index'); // Clean and reusable!
});

// ❌ Bad: Repeated assertion logic in every test
test('uses SSR', async ({ page }) => {
    await page.goto('/');
    const htmlContent = await page.content();
    expect(htmlContent).toContain('id="app"');
    expect(htmlContent).toContain('data-page');
    // ... 10 more lines of repeated code in every test
});
```

### Performance Guidelines

**Database Optimization:**

```php
// ❌ Bad: N+1 query problem
$users = User::all();
foreach ($users as $user) {
    echo $user->posts->count(); // Queries for each user
}

// ✅ Good: Eager loading
$users = User::withCount('posts')->get();
foreach ($users as $user) {
    echo $user->posts_count; // Single query
}
```

**Caching Strategies:**

- Use Redis for session/cache (already configured)
- Cache expensive queries: `Cache::remember('key', $ttl, fn() => ...)`
- Clear cache after updates: `Cache::forget('key')`

**Frontend Performance:**

- Lazy load heavy components: `defineAsyncComponent()`
- Use `v-show` for frequent toggles, `v-if` for conditional rendering
- Optimize images: use `loading="lazy"` attribute

### Error Handling

**Fail Fast Principle:**

```php
// ✅ Good: Validate early, fail fast
public function updateProfile(User $user, array $data): void
{
    if (!isset($data['email'])) {
        throw new InvalidArgumentException('Email is required');
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Invalid email format');
    }

    $user->update($data);
}
```

**Exception Handling:**

- Use specific exception types
- Log errors for debugging: `Log::error($message, $context)`
- Return user-friendly messages to frontend
- Don't catch exceptions you can't handle

### Code Review Checklist

Before requesting review, ensure:

- [ ] Code runs without errors
- [ ] All tests pass (`php artisan test`, `npm run test:e2e`)
- [ ] Static analysis passes (`composer analyse`)
- [ ] Code is formatted (`composer lint`, `npm run format`)
- [ ] No security vulnerabilities introduced
- [ ] No N+1 queries or performance issues
- [ ] PHPDoc/JSDoc added for public methods
- [ ] Commit messages follow conventional format
- [ ] No sensitive data in commits
- [ ] Frontend assets built (`npm run build`)

## Known Patterns & Conventions

### Inertia Page Resolution

Pages can be rendered from core or modules:

```php
// Core pages
return inertia('Dashboard');  // resources/js/pages/Dashboard.vue

// Module pages (namespace syntax)
return inertia('Auth::Login');  // modules/Auth/resources/js/pages/Login.vue
return inertia('Settings::Index');  // modules/Settings/resources/js/pages/Index.vue
```

### Server-Side Rendering (SSR)

**Architecture:**

Saucebase uses a two-level SSR control system:

1. **Boot level** (config): `'enabled' => true` ensures the SSR server starts
2. **Request level** (middleware): Disables SSR by default for each request
3. **Response level** (macros): Controllers opt-in with `->withSSR()` or opt-out with `->withoutSSR()`

**How it works:**

- SSR server runs continuously (started by `php artisan inertia:start-ssr`)
- `HandleInertiaRequests` middleware disables SSR at request start
- Controllers use `->withSSR()` to enable or `->withoutSSR()` to ensure disabled
- Macros override the middleware's default for that specific response

**Default**: SSR is disabled (opt-in via controller)

**Technical Implementation:**
The `HandleInertiaRequests` middleware overrides the `handle()` method to set `Config::set('inertia.ssr.enabled', false)` before processing each request. This ensures SSR is disabled by default, while the `->withSSR()` and `->withoutSSR()` macros can override this setting on a per-response basis.

**To enable or disable SSR per page**, use `->withSSR()` or `->withoutSSR()` in your controller:

```php
// Enable SSR (for public pages that need SEO)
return Inertia::render('Index')->withSSR();

// Disable SSR (for authenticated pages)
return Inertia::render('Dashboard')->withoutSSR();

// Default behavior (respects config setting)
return Inertia::render('About');
```

**Configuration Flexibility:**

The macros work regardless of your config default:

```php
// Scenario 1: SSR disabled by default (recommended)
// config/inertia.php: 'enabled' => false
return Inertia::render('Index')->withSSR();      // Enable for this page
return Inertia::render('Dashboard');              // No SSR (uses config default)

// Scenario 2: SSR enabled by default
// config/inertia.php: 'enabled' => true
return Inertia::render('Index');                  // SSR enabled (uses config default)
return Inertia::render('Dashboard')->withoutSSR(); // Disable for this page
```

**Example with multiple pages**:

```php
class ProductController extends Controller
{
    // Public list - use SSR for SEO
    public function index()
    {
        return Inertia::render('Products/Index')
            ->with('products', Product::all())
            ->withSSR();
    }

    // Admin edit - explicitly disable SSR
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit')
            ->with('product', $product)
            ->withoutSSR();
    }
}
```

**Why enable SSR?**

- Public pages that need SEO (landing pages, blog posts, products)
- Faster first contentful paint for visitors
- Better social media sharing previews

**Why disable SSR?**

- Authenticated pages (dashboards, admin panels)
- Better performance (no server-side rendering overhead)
- Pages with real-time data that needs to be fresh

**To verify SSR is working**: View page source (Ctrl+U) - you should see full HTML content, not just an empty #app div.

### Ziggy Route Helper

**Integration:**

Saucebase uses Ziggy (v2.0) to make Laravel routes available in JavaScript/TypeScript.

**Client-Side Rendering (CSR):**

- Routes injected via `@routes` Blade directive in `app.blade.php`
- ZiggyVue plugin provides global `route()` function
- TypeScript definitions in `global.d.ts`

**Server-Side Rendering (SSR):**

- Routes shared via Inertia middleware props (`HandleInertiaRequests.php`)
- SSR server receives route data from `page.props.ziggy`
- ZiggyVue plugin initialized with explicit route data

**Why middleware sharing?**
This is the official recommended pattern for Inertia.js + Ziggy SSR integration. The `@routes` Blade directive doesn't work in SSR (no DOM), so routes must be shared via Inertia's prop system. This is the same architectural pattern used for sharing `auth`, `flash`, `locale`, and other global data.

**Usage:**

```typescript
// Named routes
route('dashboard'); // /dashboard
route('settings.profile'); // /settings/profile

// Routes with parameters
route('user.show', { id: 1 }); // /users/1
route('locale', { locale: 'pt_BR' }); // /locale/pt_BR

// Route checking
route().has('dashboard'); // Check if route exists
route().current('dashboard'); // Check if current route
route().current('settings.*'); // Wildcard matching
```

**Configuration:**

Routes are filtered in `config/ziggy.php`. By default, all routes are exposed.
Consider using the `except` option to hide admin/internal routes:

```php
return [
    'except' => ['admin.*', 'sanctum.*'],
];
```

**Security:**

⚠️ Route exposure is NOT a security mechanism. Always implement proper:

- Authentication (who can access)
- Authorization (what they can do)
- CSRF protection
- Input validation

Route filtering is for:

- Reducing frontend bundle size
- Hiding internal route names (obscurity, not security)
- Improving developer experience

### Laravel Macros

**Centralized Macro Management**: All application macros are defined in `app/Providers/MacroServiceProvider.php` to keep them organized and discoverable.

**Current Macros:**

- `Inertia\Response::withSSR()` - Enable SSR for a specific Inertia response
- `Inertia\Response::withoutSSR()` - Disable SSR for a specific Inertia response

See the [SSR section](#server-side-rendering-ssr) for detailed usage examples.

**Adding New Macros:**

When you need to extend framework classes (Illuminate\Http\Request, Illuminate\Support\Collection, etc.) with custom methods:

1. Add the macro to `MacroServiceProvider::boot()` method
2. Organize macros into protected methods by category (e.g., `registerInertiaMacros()`, `registerRequestMacros()`)
3. Update this documentation with the new macro

Example:

```php
// app/Providers/MacroServiceProvider.php
protected function registerCollectionMacros(): void
{
    Collection::macro('toSelectOptions', function () {
        /** @var Collection $this */
        return $this->map(fn($item) => [
            'value' => $item->id,
            'label' => $item->name,
        ]);
    });
}

public function boot(): void
{
    $this->registerInertiaMacros();
    $this->registerCollectionMacros(); // Add new macro group
}
```

**Why use MacroServiceProvider?**

- ✅ Centralized location for all macros (easy to find)
- ✅ Better organization than scattering macros across multiple providers
- ✅ Clear separation of concerns (AppServiceProvider for app config, MacroServiceProvider for macros)
- ✅ Easier to maintain and document

### Translations

- Core translations: `lang/` (Portuguese and English by default)
- Module translations: `modules/<Name>/lang/`
- Frontend: `laravel-vue-i18n` with async loading
- Backend: Laravel's translation system with module support

### Navigation

Uses Spatie Laravel Navigation for menu management. Configure in `NavigationServiceProvider`.

### Permissions

Uses Spatie Laravel Permission for role/permission management. Default roles seeded:

- Admin role (seeded via `RolesDatabaseSeeder`)
- User role (seeded via `RolesDatabaseSeeder`)

Default middleware checks: `role:admin|user`

### Admin Panel

Filament 4 admin panel available at `/admin`. After installing the Auth module, default credentials:

- Email: `chef@saucebase.dev`
- Password: `secretsauce`

### Email Testing

Mailpit available at `http://localhost:8025` for viewing sent emails during development.

## Troubleshooting

### Module Not Found Errors

1. Check `modules_statuses.json` - ensure module is enabled (`true`)
2. Run `composer dump-autoload`
3. Clear caches: `php artisan optimize:clear`
4. Rebuild frontend: `npm run build` or restart `npm run dev`

### Frontend Build Issues

```bash
# Clear Laravel caches
php artisan optimize:clear

# Reinstall Node modules
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Database Connection Issues

Wait for MySQL to be ready (10-30 seconds on first start):

```bash
docker compose up -d --wait
docker compose ps mysql
docker compose logs mysql
```

## Important Notes

- Always rebuild frontend assets after enabling/disabling modules
- Module routes are automatically loaded from `modules/*/routes/web.php` and `modules/*/routes/api.php`
- Module migrations are auto-discovered when modules are enabled
- The `module-loader.js` handles all module asset discovery - don't bypass it
- SSL certificates support wildcard domains for multi-tenancy out of the box
- Xdebug is available in Docker with `XDEBUG_MODE=debug` (default in `.env`)
