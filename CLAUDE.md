# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Saucebase is a modular Laravel 12 SaaS starter kit built on the VILT stack (Vue 3, Inertia.js, Laravel, Tailwind CSS 4). It follows a "copy-and-own" philosophy where modules are installable feature packs that live in your repository.

**Tech Stack:**
- Backend: Laravel 12, PHP 8.4+
- Frontend: Vue 3, TypeScript 5.8, Inertia.js 2.0, Vite 6
- Styling: Tailwind CSS 4, shadcn-compatible component structure
- Admin: Filament 4
- State: Pinia with persistence
- Testing: PHPUnit (backend), Playwright (E2E)

## Development Commands

### Initial Setup
```bash
./bin/setup-env  # Bootstrap: Docker, SSL certs, deps, migrations, seeds
```

### Development Server
```bash
# Option 1: Full-stack dev with Docker
docker compose up -d
npm run dev

# Option 2: All services via Composer (server, queue, logs, vite)
composer dev
```

### Backend

**IMPORTANT:** This project runs in Docker. ALL `php artisan` commands MUST be prefixed with `docker compose exec workspace`, otherwise they will fail.

```bash
# Run artisan commands inside Docker workspace
docker compose exec workspace php artisan <command>

# Common artisan commands (all require docker compose exec workspace prefix)
docker compose exec workspace php artisan migrate
docker compose exec workspace php artisan db:seed
docker compose exec workspace php artisan queue:work
docker compose exec workspace php artisan pail  # Log viewer
docker compose exec workspace php artisan tinker
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
docker compose exec workspace php artisan test

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
- Or use: `docker compose exec workspace php artisan module:enable <ModuleName>`

**Install a Module:**
```bash
composer require saucebase/<module-name>
composer dump-autoload
docker compose exec workspace php artisan module:enable <ModuleName>
docker compose exec workspace php artisan module:migrate <ModuleName>
npm run build
```

**Remove a Module:**
```bash
docker compose exec workspace php artisan module:delete <ModuleName>  # NEVER manually rm -rf a module
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
}

export const afterMount = async (app?: App) => {
  // Runs after Vue app mounts (e.g., initialize services, load data)
}
```

**Pinia Stores:**
- Global stores: `resources/js/stores/` (e.g., `ui.ts`, `localization.ts`)
- Module stores: `modules/<Module>/resources/js/stores/` (loaded in module setup)
- Persistence configured via `pinia-plugin-persistedstate`

**i18n:**
- Language files: `lang/<locale>.json` (e.g., `lang/pt_BR.json`)
- Use `$t('key')` in templates, `trans('key')` in backend
- All user-facing strings MUST use i18n system

## File Structure Conventions

- **Lowercase folders:** Use `components/`, `pages/`, `layouts/`, `stores/` (not `Components/`, etc.)
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

1. **Docker environment:** ALL `php artisan` commands MUST be prefixed with `docker compose exec workspace` - running artisan commands directly will fail
2. **Never commit secrets:** All sensitive config goes in `.env` (gitignored)
3. **Single-line commits:** Body and footer rejected by commitlint
4. **No manual module deletion:** Use `docker compose exec workspace php artisan module:delete <ModuleName>`
5. **Portuguese translations required:** Add to `lang/pt_BR.json` when adding UI text
6. **Module status updates:** Update `modules_statuses.json` when enabling/disabling modules
7. **Directory naming:** Use lowercase for component folders (follow existing convention)

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
- Pinia stores persisted to localStorage
- Tailwind CSS 4 with `@tailwindcss/vite` plugin

### Vite Configuration
- HTTPS dev server with self-signed certs (`docker/development/ssl/`)
- Path aliases: `@` → `resources/js/`, `@modules` → `modules/`
- Icons via `unplugin-icons/vite` (auto-installs from `@iconify/json`)

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
   export default { paths: ['js/app.ts', 'css/app.css'] }
   ```
2. Assets auto-collected by `module-loader.js`

### Add Pinia Store
- Core store: `resources/js/stores/<name>.ts`
- Module store: `modules/<Module>/resources/js/stores/<name>.ts`
- Register in module's `app.ts` setup function

## Security Considerations

- Input validation via Laravel FormRequest classes
- CSRF protection enabled (Inertia handles tokens)
- XSS prevention via Vue's template escaping
- SQL injection prevention via Eloquent ORM
- Authentication via Laravel Sanctum
- Authorization via Spatie Laravel Permission
