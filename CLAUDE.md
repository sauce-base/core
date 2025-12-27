# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Saucebase is a modular Laravel 12 SaaS starter kit built on the VILT stack (Vue 3, Inertia.js, Laravel, Tailwind CSS 4). It follows a "copy-and-own" philosophy where modules are installable feature packs that live in your repository.

**Tech Stack:**

- Backend: Laravel 12, PHP 8.4+
- Frontend: Vue 3, TypeScript 5.8, Inertia.js 2.0, Vite 6
- Styling: Tailwind CSS 4, shadcn-compatible component structure
- Admin: Filament 4
- State: Vue Composition API with VueUse composables
- Testing: PHPUnit (backend), Playwright (E2E)

## Development Commands

### Initial Setup

```bash
./bin/setup-env  # Bootstrap: Docker, SSL certs (if mkcert installed), deps, migrations, seeds
```

**SSL Setup:**

- If `mkcert` is installed, setup automatically generates HTTPS certificates
- Install with: `brew install mkcert && mkcert -install` (macOS)
- Without mkcert, app runs on HTTP

### Development Server

```bash
# Start Docker services (app, nginx, mysql, redis, mailpit)
docker compose up -d

# Start Vite dev server (frontend hot reload)
npm run dev

# Start full-stack dev environment (server, queue, logs, vite) inside Docker
docker compose exec app composer dev
```

### Backend

**IMPORTANT:** This project runs in Docker. ALL `php artisan` commands MUST be prefixed with `docker compose exec app`, otherwise they will fail.

```bash
# Run artisan commands inside Docker
docker compose exec app php artisan <command>

# Common artisan commands (all require docker compose exec app prefix)
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan queue:work
docker compose exec app php artisan pail  # Log viewer
docker compose exec app php artisan tinker
```

### Frontend

```bash
npm run dev          # Vite dev server with hot reload
npm run build        # Production build (includes SSR: vite build && vite build --ssr)
npm run build:ssr    # Same as build
```

### Testing

```bash
# Backend/unit tests
composer test             # Runs php artisan test inside Docker
docker compose exec app php artisan test

# E2E tests (Playwright)
npm run test              # Run all E2E tests
npm run test:e2e:ui       # Open Playwright UI
npm run test:e2e:headed   # Run tests with browser visible
npm run test:e2e:debug    # Debug mode
npm run test:e2e:report   # View test report
```

### Linting & Formatting

```bash
# PHP
vendor/bin/pint          # Format PHP code (Laravel preset)
composer lint            # Same as above

# JavaScript/TypeScript
npm run lint             # ESLint on resources/ and modules/*/resources/
npm run format           # Prettier format
npm run format:check     # Check formatting without changes

# Static Analysis
composer analyse         # PHPStan level 5 (runs with --memory-limit=2G)
```

## Modular Architecture

### Module System

Modules are self-contained feature packs in `modules/<ModuleName>/` managed by nwidart/laravel-modules. Each module has:

```
modules/<ModuleName>/
├── app/                    # Controllers, services, providers
├── resources/
│   ├── js/
│   │   ├── pages/         # Vue pages (accessed via ModuleName::PagePath)
│   │   ├── components/    # Vue components
│   │   └── app.ts         # Optional module setup/lifecycle hooks
│   └── css/               # Module-specific styles
├── routes/
│   ├── web.php           # Module web routes
│   └── api.php           # Module API routes
├── database/
│   └── migrations/       # Module migrations
├── config/               # Module config files
├── vite.config.js        # Module asset paths (exports { paths: [...] })
└── composer.json         # Module dependencies (merged at runtime)
```

### Module Management

**Enable/Disable Modules:**

- Edit `modules_statuses.json` (set module name to `true` or `false`)
- Or use: `docker compose exec app php artisan module:enable <ModuleName>`

**Install a Module:**

```bash
composer require saucebase/<module-name>
composer dump-autoload
docker compose exec app php artisan module:enable <ModuleName>
docker compose exec app php artisan module:migrate <ModuleName>
npm run build
```

**Remove a Module:**

```bash
docker compose exec app php artisan module:delete <ModuleName>  # NEVER manually rm -rf a module
```

### How Modules Work

1. **Discovery:** `module-loader.js` reads `modules_statuses.json` and scans enabled modules
2. **Asset Collection:** Main Vite config calls `collectModuleAssetsPaths()` to gather module asset paths from each module's `vite.config.js`
3. **Page Resolution:** `resolveModularPageComponent()` (in `resources/js/lib/utils.ts`) handles namespaced page syntax like `Auth::Login` → `modules/Auth/resources/js/pages/Login.vue`
4. **Module Setup:** Each module can export `setup()` and `afterMount()` functions in `resources/js/app.ts` for initialization
5. **Dependencies:** Module `composer.json` files are merged at runtime via composer-merge-plugin

### Frontend Integration

**Component Resolution:**

- Core pages: `resources/js/pages/Index.vue` → reference as `Index`
- Module pages: `modules/Auth/resources/js/pages/Login.vue` → reference as `Auth::Login`

**Module Lifecycle Hooks (in module's `resources/js/app.ts`):**

```typescript
export const setup = async (app?: App) => {
    // Runs before Vue app mounts (e.g., register global components, plugins)
};

export const afterMount = async (app?: App) => {
    // Runs after Vue app mounts (e.g., initialize services, load data)
};
```

**i18n:**

- Language files: `lang/<locale>.json` (e.g., `lang/pt_BR.json`)
- Use `$t('key')` in templates, `trans('key')` in backend
- All user-facing strings MUST use i18n system

## File Structure Conventions

- **Lowercase folders:** Use `components/`, `pages/`, `layouts/`, `composables/` (not `Components/`, etc.)
- **Vue components:** Single File Components in `.vue` files
- **Module namespacing:** Controllers in `modules/<Module>/app/Http/Controllers/`
- **Routes:** Module routes auto-discovered from `modules/<Module>/routes/web.php` and `api.php`

## Code Quality Standards

### Commit Messages

**Enforced via commitlint:**

- Format: `<type>: <subject>` (single line, max 150 chars)
- Types: `feat|fix|docs|style|refactor|perf|test|chore|ci|build|revert`
- Example: `feat: add OAuth callback`
- **No bodies or footers allowed** (no co-author, no "Generated with..." text)
- Subject must be lowercase, not sentence-case

### PHP Standards

- **Formatter:** Laravel Pint (runs `vendor/bin/pint`)
- **Static Analysis:** PHPStan level 5 (configured in `phpstan.neon`)
    - Analyzes `app/` and `modules/`
    - Ignores `env()` calls in module config files (cached config requirement)
- **Style:** Follow PSR-12 via Pint's Laravel preset

### JavaScript/TypeScript Standards

- **Linter:** ESLint 9 with Vue + TypeScript configs
- **Formatter:** Prettier with Tailwind plugin
- **Type checking:** TypeScript strict mode via `vue-tsc`

### Testing Requirements

- **Backend:** PHPUnit tests in `tests/`
- **E2E:** Playwright tests in `tests/e2e/` (core) and `modules/*/tests/e2e/` (modules)
- **Test Discovery:** `playwright.config.ts` auto-collects module test configs via `collectModulePlaywrightConfigs()`

## Important Constraints

1. **Docker environment:** ALL `php artisan` commands MUST be prefixed with `docker compose exec app` - running artisan commands directly will fail
2. **Never commit secrets:** All sensitive config goes in `.env` (gitignored)
3. **Single-line commits:** Body and footer rejected by commitlint
4. **No manual module deletion:** Use `docker compose exec app php artisan module:delete <ModuleName>`
5. **Portuguese translations required:** Add to `lang/pt_BR.json` when adding UI text
6. **Module status updates:** Update `modules_statuses.json` when enabling/disabling modules
7. **Directory naming:** Use lowercase for component folders (follow existing convention)
8. **Xdebug enabled by default:** For active development. Disable with `XDEBUG_MODE=off` in `.env` if needed for performance

## Key Architectural Patterns

### Inertia.js SPA

- SSR enabled (builds client + server bundles)
- Shared data via `HandleInertiaRequests` middleware
- Error pages handled via `bootstrap/app.php` exception handler (404, 403, 500, 503 → `Error.vue`)

### Middleware Stack

- `HandleInertiaRequests`: Shares global props (user, flash messages, etc.)
- `HandleLocalization`: Sets locale from session/preference
- Spatie Permission middleware: `role`, `permission`, `role_or_permission`

### Theme & Persistence

- Dark/light mode via `@vueuse/core` `useColorMode()`
- State management via Vue Composition API with VueUse composables
- Tailwind CSS 4 with `@tailwindcss/vite` plugin

### Vite Configuration

- HTTPS dev server with mkcert certificates (`docker/ssl/`)
- Path aliases: `@` → `resources/js/`, `@modules` → `modules/`
- Icons via `unplugin-icons/vite` (auto-installs from `@iconify/json`)

### Xdebug Configuration

**Xdebug is enabled by default** for active development:

- **Mode:** `debug` (enabled)
- **Port:** `9003`
- **IDE Key:** `VSCODE`
- **Client:** `host.docker.internal:9003`

**VS Code Setup:**

1. Install PHP Debug extension
2. Add to `.vscode/launch.json`:
    ```json
    {
        "name": "Listen for Xdebug",
        "type": "php",
        "request": "launch",
        "port": 9003,
        "pathMappings": {
            "/var/www": "${workspaceFolder}"
        }
    }
    ```
3. Set breakpoints and press F5

**PhpStorm Setup:**

1. Go to Settings → PHP → Servers
2. Add server: Name=`Docker`, Host=`localhost`, Port=`80`
3. Set path mapping: `/var/www` → your project root
4. Enable "Start Listening for PHP Debug Connections"

**Disable Xdebug (for better performance):**

```bash
# Add to .env
XDEBUG_MODE=off

# Restart containers
docker compose restart app
```

### SSL with mkcert (Recommended for Local Development)

The setup script automatically configures HTTPS if mkcert is installed:

```bash
# Install mkcert (one-time setup)
brew install mkcert          # macOS
choco install mkcert         # Windows

# Install local CA
mkcert -install

# Run setup (auto-generates certificates)
./bin/setup-env
```

**Benefits:**

- ✅ HTTPS on localhost (https://localhost)
- ✅ No browser warnings
- ✅ Automatic certificate generation
- ✅ Works with service workers and PWAs
- ✅ **Wildcard support** for multi-tenancy (https://\*.localhost)

**Certificates location:** `docker/ssl/`

### Multi-Tenancy Support

The SSL certificates are generated with wildcard support, enabling multi-tenant applications:

**Supported domains:**

- `https://localhost` - Main domain
- `https://tenant1.localhost` - Tenant subdomain
- `https://acme.localhost` - Another tenant
- `https://any-name.localhost` - Any subdomain works!

**Setup for multi-tenancy:**

1. **No hosts file needed** - `*.localhost` resolves automatically on most systems

2. **Install a multi-tenancy package:**

    ```bash
    # Popular options:
    composer require spatie/laravel-multitenancy
    # or
    composer require stancl/tenancy
    ```

3. **Configure tenant identification** in your package config:

    ```php
    // Identify tenants by subdomain
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,
    ```

4. **Test with subdomains:**

    ```bash
    # Main app
    https://localhost

    # Tenant apps
    https://tenant1.localhost
    https://tenant2.localhost
    ```

**Note:** If subdomains don't resolve on your system, add to `/etc/hosts`:

```
127.0.0.1 tenant1.localhost
127.0.0.1 tenant2.localhost
```

Or use **dnsmasq** for automatic wildcard DNS resolution (recommended for many tenants).

### Alternative: Using ngrok (For Public HTTPS)

For sharing with clients, testing OAuth, or webhooks, use **ngrok**:

```bash
# Install ngrok
brew install ngrok           # macOS
# Or download from: https://ngrok.com/download

# Start ngrok tunnel
ngrok http 443

# Get public HTTPS URL: https://abc123.ngrok.io
# Update .env: APP_URL=https://abc123.ngrok.io
```

**When to use ngrok:**

- Testing OAuth providers (Google, GitHub, etc.)
- Webhook testing (Stripe, PayPal, etc.)
- Sharing development with clients/team
- Mobile device testing
- Public demo URLs

### Navigation System

> **Note:** The navigation system has been recently refactored to use a simplified architecture based on Spatie's laravel-navigation package. Comprehensive documentation and tests are coming soon.

## Running Tests Before PRs

**Checklist:**

1. Format code: `npm run format && vendor/bin/pint`
2. Lint: `npm run lint`
3. Static analysis: `composer analyse`
4. Run tests: `composer test && npm run test` (note: `composer test` runs artisan inside Docker)
5. Build check: `npm run build`
6. Update translations if UI text changed
7. Update `modules_statuses.json` if modules added/removed

**Note:** The `composer test`, `composer lint`, and `composer analyse` scripts are configured to run inside Docker automatically.

## Common Tasks

### Add a New Module Route

1. Add route in `modules/<Module>/routes/web.php`
2. Reference in controller/view as namespaced route
3. Frontend page at `modules/<Module>/resources/js/pages/<Page>.vue`
4. Reference in Inertia: `Inertia::render('ModuleName::Page')`

### Add a Vue Component

- Core: `resources/js/components/<component>.vue`
- Module: `modules/<Module>/resources/js/components/<component>.vue`
- Use lowercase folder names

### Add Module Assets

1. Create `modules/<Module>/vite.config.js`:
    ```js
    export default { paths: ['js/app.ts', 'css/app.css'] };
    ```
2. Assets auto-collected by `module-loader.js`

### Add Navigation Items

> **Note:** Navigation documentation is being updated. See the Navigation System section above for status.

## Security Considerations

- Input validation via Laravel FormRequest classes
- CSRF protection enabled (Inertia handles tokens)
- XSS prevention via Vue's template escaping
- SQL injection prevention via Eloquent ORM
- Authentication via Laravel Sanctum
- Authorization via Spatie Laravel Permission
