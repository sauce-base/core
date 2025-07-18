# CLAUDE.md - Development Guidelines

**CRITICAL**: This file contains mandatory guidelines that Claude Code MUST follow exactly. No exceptions.

## 🚀 Project Overview

**Tadone** is a modern Laravel SaaS starter kit designed for rapid development of scalable SaaS applications. Built with the VILT stack (Vue, Inertia, Laravel, Tailwind), it provides a solid foundation for building and launching SaaS products quickly.

### Key Features
- **Modern Stack**: Laravel 12 + Vue 3 + TypeScript + Inertia.js + Tailwind CSS
- **Authentication**: Multi-provider social login (Google, GitHub, Facebook)
- **Components**: shadcn/ui with reka-ui implementation
- **Testing**: Comprehensive PHP (Pest) and E2E (Playwright) testing
- **Developer Experience**: Hot reload, TypeScript, ESLint, PHP CS Fixer
- **Production Ready**: Docker, Redis, PostgreSQL, optimized build pipeline

## 🚨 MANDATORY RULES - NEVER VIOLATE THESE

### Git Commits - STRICT ENFORCEMENT
- **FORMAT**: Single line only, no body, no multi-line messages
- **NO AUTHORS**: Never add Co-Authored-By, Generated with Claude, or any attribution
- **PATTERN**: `type: description` (e.g., `feat: add user authentication`)
- **GROUPING**: Logical changes together, infrastructure separate from features
- **VERIFICATION**: Always run `git status` after commit to verify success
- **BRANCH MANAGEMENT**: Always create/switch to a branch when working in a GitHub issue

### Pull Requests - MANDATORY FORMAT  
- **TITLE**: Clear, descriptive summary
- **BODY**: Must include "## Summary" and "## Test plan" sections
- **NO ATTRIBUTION**: Never add "Generated with Claude" or similar

### Code Quality - ALWAYS RUN THESE
- **BEFORE COMMIT**: Always run `./vendor/bin/pint` and `npm run lint`
- **TESTS**: Run `composer test` after backend changes
- **BUILD**: Run `npm run build` after frontend changes
- **VERIFY**: Check all commands pass before committing

## 📋 Development Commands - USE THESE EXACTLY

### Quick Start Commands
```bash
# Start development (run this first)
sail up  # Starts Laravel + Vite + queue worker + logs

# Quality checks (run before commit)
./vendor/bin/pint     # PHP formatting
./vendor/bin/phpstan  # PHP static analysis  
npm run lint          # JS/Vue linting
composer test         # PHP tests
npm run build         # Production build test
```

### Backend (Laravel/PHP)
- `sail up` - **MAIN COMMAND**: Starts everything (Laravel + Vite + workers)
- `composer test` - Run all PHP tests
- `php artisan test --filter=TestName` - Run specific test
- `./vendor/bin/pint` - **REQUIRED**: Format PHP code
- `./vendor/bin/phpstan` - **REQUIRED**: Static analysis
- `php artisan migrate` - Database migrations
- `php artisan db:seed` - Seed database
- `php artisan optimize:clear` - Clear all caches

### Frontend (Vue/TypeScript)
- `npm run dev` - Vite dev server (usually run via `sail up`)
- `npm run build` - **REQUIRED**: Production build verification
- `npm run lint` - **REQUIRED**: ESLint with auto-fix

### Testing (E2E with Playwright)
- `npm run test:e2e` - **USE THIS**: Headless tests with results
- `npm run test:e2e:headed` - Visual debugging
- `npm run test:e2e:debug` - Debug mode
- **NEVER USE**: `npm run test:e2e:ui` (no results output)

## 🏗️ Architecture - FOLLOW THESE PATTERNS

### Stack Overview
- **Backend**: Laravel 12 + PHP 8.2+ + PostgreSQL + Redis
- **Frontend**: Vue 3 + TypeScript + Inertia.js + Tailwind CSS
- **Components**: shadcn/ui with reka-ui implementation
- **Testing**: Pest PHP + Playwright E2E

### Component Patterns - MANDATORY
- **Location**: `resources/js/Components/` for reusable components
- **UI Components**: Use shadcn/ui pattern with reka-ui
- **Import**: Use `@/Components/ui/button` style imports
- **Styling**: Tailwind CSS classes, no custom CSS unless required

### File Organization - STRICT STRUCTURE
```
resources/js/
├── Components/
│   ├── ui/           # shadcn/ui components
│   ├── layout/       # Layout components  
│   └── forms/        # Form components
├── Pages/            # Inertia pages
├── lib/              # Utilities
└── validation/       # Zod schemas
```

## 🧪 Testing Requirements - ALWAYS FOLLOW

### E2E Testing (Playwright)
- **LOCATION**: `tests/e2e/` directory only
- **PATTERN**: Page Object Model mandatory
- **SELECTORS**: Use `data-testid` attributes (priority #1)
- **NAMING**: `data-testid="field-name"` for inputs, `data-testid="field-name-error"` for errors
- **BEFORE TESTS**: Must run `sail up` first
- **EXECUTION**: Use `npm run test:e2e` for results

### PHP Testing (Pest)
- **RUN**: `composer test` before every commit
- **LOCATION**: `tests/Feature/` and `tests/Unit/`
- **DATABASE**: Use `RefreshDatabase` trait

## 🗄️ Database & Migrations - STRICT WORKFLOW

### Pre-v1.0 Development Phase (Current)
- **SINGLE MIGRATION**: Keep all schema changes in main migration file until first release
- **SQUASH REGULARLY**: Periodically squash migration history to keep it clean
- **NO ROLLBACKS**: Don't worry about rollback safety during rapid development
- **FRESH RESET**: Use `sail artisan migrate:fresh --seed` for development
- **ATOMIC COMMITS**: Group related schema changes in single commits

### Post-v1.0 Production Phase (Future)
- **SEPARATE MIGRATIONS**: Each schema change gets its own migration file
- **FORWARD-ONLY**: Never rollback migrations in production (create new migrations to fix)
- **ATOMIC OPERATIONS**: Each migration does one thing well
- **NAMING CONVENTION**: Use Laravel standard (`create_`, `add_`, `modify_`, `drop_`)
- **SCHEMA ONLY**: Keep migrations for structure, use seeders for data
- **PREVIEW CHANGES**: Use `--pretend` flag to review SQL before executing
- **MAINTENANCE MODE**: Use `sail artisan down` for potentially disruptive migrations

### Migration Best Practices (Always Follow)
- **NO DYNAMIC REFERENCES**: Never reference model properties in migrations
- **BACKWARD COMPATIBILITY**: Ensure migrations don't break existing data
- **INDEX MANAGEMENT**: Add indexes for foreign keys and frequently queried columns
- **CHUNK LARGE OPERATIONS**: Break big changes into smaller, manageable steps
- **SEPARATE CONCERNS**: Schema changes in migrations, data changes in seeders

### Common Migration Commands
```bash
# Development (Pre-v1.0)
sail artisan migrate:fresh --seed    # Reset database with fresh schema
sail artisan migrate:refresh --seed  # Rollback all and re-run

# Production (Post-v1.0)
sail artisan migrate --pretend      # Preview SQL without executing
sail artisan migrate               # Run pending migrations
sail artisan migrate:status       # Check migration status
```

## 🔐 Security Standards - OWASP TOP 10 COMPLIANCE

### Backend Security (PHP/Laravel)
- **INPUT VALIDATION**: Always validate and sanitize user input using Laravel's validation
- **SQL INJECTION**: Use Eloquent ORM or prepared statements, never raw queries with user input
- **AUTHENTICATION**: Use Laravel's built-in authentication, never roll your own
- **AUTHORIZATION**: Implement proper authorization checks using Gates/Policies
- **CSRF PROTECTION**: Ensure CSRF tokens are present on all forms
- **XSS PREVENTION**: Use Laravel's automatic escaping, never echo raw user input
- **SECURE HEADERS**: Implement security headers (HSTS, CSP, etc.)
- **ENVIRONMENT SECRETS**: Never commit secrets, use `.env` files properly

### Frontend Security (Vue/TypeScript)
- **XSS PREVENTION**: Sanitize user input before rendering
- **CSRF TOKENS**: Include CSRF tokens in all AJAX requests
- **SECURE STORAGE**: Never store sensitive data in localStorage/sessionStorage
- **INPUT VALIDATION**: Validate on both client and server side
- **SECURE COMMUNICATION**: Use HTTPS for all API calls
- **DEPENDENCY SECURITY**: Regularly update npm packages for security patches

## 🏗️ Backend Architecture - LARAVEL GUIDELINES

### Controller Standards
- **THIN CONTROLLERS**: Keep controllers lean, delegate business logic
- **SINGLE RESPONSIBILITY**: Each controller method should handle one action
- **LARAVEL CONVENTIONS**: Follow Laravel naming conventions for methods
- **VALIDATION**: Use Form Request classes for complex validation
- **RETURN TYPES**: Use proper HTTP response codes and formats
- **EXCEPTION HANDLING**: Let Laravel handle exceptions, use custom handlers when needed

### Model Standards
- **ELOQUENT FIRST**: Use Eloquent ORM, avoid raw SQL unless necessary
- **MASS ASSIGNMENT**: Define `$fillable` or `$guarded` properties
- **RELATIONSHIPS**: Define relationships properly with type hints
- **MUTATORS/ACCESSORS**: Use for data transformation
- **SCOPE METHODS**: Use local scopes for reusable query logic

### API Response Standards
- **CONSISTENT FORMAT**: Follow Laravel's resource transformation patterns
- **HTTP STATUS CODES**: Use appropriate HTTP status codes (200, 201, 400, 404, etc.)
- **ERROR HANDLING**: Return structured error responses with clear messages
- **PAGINATION**: Use Laravel's built-in pagination for large datasets
- **VERSIONING**: Consider API versioning for future compatibility

### Architecture Notes
- **FUTURE DECISION**: Controller architecture (thin controllers with actions) to be decided later
- **LARAVEL FIRST**: Always follow Laravel conventions and best practices
- **SECURITY FIRST**: OWASP Top 10 compliance is mandatory

## 🔒 Laravel Security Best Practices - MANDATORY COMPLIANCE

### Authentication Standards
- **SANCTUM FIRST**: Use Laravel Sanctum for API authentication (already implemented)
- **LARAVEL AUTH**: Use Laravel's built-in authentication scaffolding
- **STRONG PASSWORDS**: Enforce password policies using `Password::defaults()`
- **THROTTLE REQUESTS**: Implement rate limiting on login routes
- **ACCOUNT LOCKOUT**: Lock accounts after failed login attempts
- **2FA READY**: Prepare for two-factor authentication implementation

### Authorization Patterns
- **GATES & POLICIES**: Use Laravel Gates and Policies for authorization
- **RBAC**: Implement Role-Based Access Control when needed
- **MIDDLEWARE**: Protect routes with authentication middleware
- **RESOURCE POLICIES**: Create policies for all models with user access
- **PERMISSION CHECKS**: Always check permissions before sensitive operations

### Data Protection
- **ELOQUENT ORM**: Use Eloquent to prevent SQL injection (never raw queries)
- **MASS ASSIGNMENT**: Define `$fillable` or `$guarded` on all models
- **VALIDATION**: Server-side validation for all user inputs
- **SANITIZATION**: Sanitize data before database storage
- **ENCRYPTION**: Use Laravel's encryption for sensitive data

### Session & Token Security
- **CSRF TOKENS**: Enable CSRF protection on all forms
- **SECURE COOKIES**: Use secure, httpOnly cookies in production
- **SESSION SECURITY**: Configure secure session settings
- **TOKEN EXPIRATION**: Set appropriate token expiration times
- **LOGOUT HANDLING**: Properly invalidate sessions on logout

### File & Upload Security
- **MIME TYPE VALIDATION**: Validate file types using Laravel rules
- **PRIVATE STORAGE**: Store uploads in private directories when possible
- **FILE SIZE LIMITS**: Set reasonable file size limits
- **VIRUS SCANNING**: Consider virus scanning for file uploads
- **DIRECT ACCESS**: Prevent direct access to uploaded files

### Environment Security
- **PRODUCTION CONFIG**: Set `APP_ENV=production` and `APP_DEBUG=false`
- **HTTPS ONLY**: Force HTTPS in production
- **SECRET MANAGEMENT**: Never commit `.env` files or secrets
- **LOG SECURITY**: Set `LOG_LEVEL=error` in production
- **DEPENDENCY UPDATES**: Regularly run `composer audit` and update

### Common Security Commands
```bash
# Check for security vulnerabilities
composer audit

# Update dependencies
composer update

# Generate secure APP_KEY
php artisan key:generate

# Clear application cache
php artisan optimize:clear

# Check routes protection
php artisan route:list --except-vendor
```

### Security Middleware Stack
- **CSRF Protection**: `\App\Http\Middleware\VerifyCsrfToken::class`
- **Rate Limiting**: `\Illuminate\Routing\Middleware\ThrottleRequests::class`
- **Authentication**: `\App\Http\Middleware\Authenticate::class`
- **Authorization**: Custom middleware for role/permission checks

## ⚡ Performance & Optimization - BASIC RECOMMENDATIONS

### Database Performance
- **EAGER LOADING**: Use `with()` to prevent N+1 query problems
- **QUERY OPTIMIZATION**: Use `select()` to limit columns when needed
- **INDEXES**: Add database indexes for frequently queried columns
- **PAGINATION**: Use Laravel's pagination instead of loading all records
- **AVOID**: Don't use `all()` on large datasets, use `get()` with limits

### Caching Strategy
- **REDIS**: Use Redis for session storage and caching (already configured)
- **QUERY CACHING**: Cache expensive database queries
- **VIEW CACHING**: Cache compiled views in production
- **ROUTE CACHING**: Cache routes in production with `php artisan route:cache`
- **CONFIG CACHING**: Cache configuration in production

### Frontend Performance
- **LAZY LOADING**: Implement lazy loading for images and components
- **CODE SPLITTING**: Use dynamic imports for large components
- **ASSET OPTIMIZATION**: Minimize and compress CSS/JS assets
- **IMAGE OPTIMIZATION**: Use appropriate image formats and sizes
- **CDN**: Consider CDN for static assets in production

### Background Processing
- **QUEUES**: Use Laravel queues for time-consuming tasks
- **HORIZON**: Use Laravel Horizon for queue monitoring (already configured)
- **ASYNC PROCESSING**: Move heavy operations to background jobs
- **BATCH PROCESSING**: Use job batching for bulk operations

### Basic Performance Commands
```bash
# Production optimizations
php artisan optimize          # Optimize application
php artisan config:cache      # Cache configuration
php artisan route:cache       # Cache routes
php artisan view:cache        # Cache views

# Clear caches during development
php artisan optimize:clear    # Clear all caches
```

### Performance Monitoring
- **QUERY DEBUGGING**: Use Laravel Debugbar in development
- **SLOW QUERIES**: Monitor slow queries in production logs
- **MEMORY USAGE**: Monitor memory usage for large operations
- **RESPONSE TIMES**: Track API response times

### Notes
- **DEVELOPER DECISION**: Specific optimizations can be implemented as needed
- **PROFILE FIRST**: Always profile before optimizing
- **MEASURE IMPACT**: Measure performance impact of optimizations

## 🚨 Error Handling & Logging - LARAVEL RECOMMENDATIONS

### Exception Handling
- **CUSTOM EXCEPTIONS**: Create custom exceptions for business logic errors
- **EXCEPTION HANDLER**: Use Laravel's exception handler for consistent error responses
- **HTTP EXCEPTIONS**: Use Laravel's HTTP exceptions (404, 403, 500, etc.)
- **VALIDATION EXCEPTIONS**: Let Laravel handle validation exceptions automatically
- **REPORT METHOD**: Use the `report()` method for logging exceptions

### Logging Standards
- **LOG LEVELS**: Use appropriate log levels (emergency, alert, critical, error, warning, notice, info, debug)
- **STRUCTURED LOGGING**: Use Laravel's logging with context arrays
- **PRODUCTION LOGGING**: Set `LOG_LEVEL=error` in production
- **DAILY ROTATION**: Use daily log rotation to manage file sizes
- **SENSITIVE DATA**: Never log passwords, tokens, or sensitive information

### Error Responses
- **API ERRORS**: Return consistent JSON error responses
- **USER FRIENDLY**: Show user-friendly messages, log technical details
- **STATUS CODES**: Use proper HTTP status codes for API responses
- **VALIDATION ERRORS**: Return structured validation error responses
- **PRODUCTION ERRORS**: Hide detailed error messages in production

### Development vs Production
- **DEVELOPMENT**: Show detailed error messages and stack traces
- **PRODUCTION**: Hide sensitive information, show generic error messages
- **APP_DEBUG**: Set to `false` in production
- **ERROR PAGES**: Create custom error pages for better UX

### Basic Error Handling Patterns
```php
// Custom Exception
class BusinessLogicException extends Exception
{
    public function report(): bool
    {
        Log::error('Business logic error: ' . $this->getMessage());
        return false;
    }
}

// Controller Error Handling
try {
    $result = $this->someOperation();
    return response()->json(['data' => $result]);
} catch (BusinessLogicException $e) {
    return response()->json(['error' => 'Operation failed'], 422);
} catch (Exception $e) {
    Log::error('Unexpected error: ' . $e->getMessage());
    return response()->json(['error' => 'Server error'], 500);
}

// Logging with Context
Log::info('User logged in', ['user_id' => $user->id, 'ip' => $request->ip()]);
```

### Vue.js Error Handling
- **ERROR BOUNDARIES**: Handle component errors gracefully
- **API ERRORS**: Handle API errors consistently
- **USER FEEDBACK**: Show user-friendly error messages
- **CONSOLE ERRORS**: Log errors to console in development only

### Monitoring (Future)
- **ERROR TRACKING**: Consider services like Sentry, Bugsnag, or Flare
- **PERFORMANCE MONITORING**: Monitor application performance
- **UPTIME MONITORING**: Monitor application availability
- **LOG ANALYSIS**: Analyze logs for patterns and issues

## 🛠️ Development Tools & Workflows - RECOMMENDED

### IDE Configuration
- **VS Code** (recommended) with extensions:
  - Laravel Extension Pack
  - Vue Language Features (Volar)
  - Tailwind CSS IntelliSense
  - GitLens

### Debugging Tools
- **Laravel Telescope** - Query/request debugging (install when needed)
- **Laravel Debugbar** - Development debugging (already available)
- **Vue DevTools** - Frontend component debugging
- **Browser DevTools** - Network, performance, console debugging

### Code Quality Tools (Already Implemented)
- **PHP CS Fixer (Pint)** - PHP code formatting
- **PHPStan** - Static analysis
- **ESLint + Prettier** - JavaScript/Vue formatting
- **Husky** - Git hooks for quality checks

### Development Workflow
- **Laravel Sail** - Consistent development environment
- **Hot Module Replacement** - Instant frontend updates
- **Database Seeding** - Consistent test data
- **Artisan Commands** - Custom commands for common tasks

### Performance Monitoring
- **Laravel Horizon** - Queue monitoring (already configured)
- **Laravel Pulse** - Application insights (consider for production)
- **Browser Performance Tools** - Core Web Vitals monitoring

### Code Review Process
- **GitHub PR Templates** - Consistent PR structure
- **Required Reviews** - At least one approval before merge
- **Status Checks** - Tests and linting must pass
- **Branch Protection** - Prevent direct pushes to main

### Development Environment Setup
```bash
# Essential development tools
sail up                    # Start development environment
sail artisan telescope:install  # Install debugging tools (optional)
npm install --save-dev @vue/devtools  # Vue debugging

# IDE workspace settings (.vscode/settings.json)
{
  "editor.formatOnSave": true,
  "typescript.preferences.importModuleSpecifier": "relative",
  "tailwindCSS.experimental.classRegex": [
    ["cva\\(([^)]*)\\)", "[\"'`]([^\"'`]*).*?[\"'`]"],
    ["cx\\(([^)]*)\\)", "(?:'|\"|`)([^']*)(?:'|\"|`)"]
  ]
}
```

## 🔧 Code Standards - NON-NEGOTIABLE

### PHP Code
- **FORMATTING**: Must run `./vendor/bin/pint` before commit
- **ANALYSIS**: Must run `./vendor/bin/phpstan` and fix issues
- **IMPORTS**: Use proper namespacing, no unused imports

### Vue/TypeScript
- **LINTING**: Must run `npm run lint` before commit
- **TYPING**: Use TypeScript properly, no `any` types
- **COMPOSITION API**: Use `<script setup lang="ts">` pattern
- **VALIDATION**: Use Zod schemas in `resources/js/validation/`

## 🎨 Frontend Component Architecture - FOLLOW CURRENT PATTERNS

### Component Organization (Established Pattern)
```
resources/js/Components/
├── ui/                    # shadcn/ui reusable components
│   ├── avatar/           # Avatar-related components
│   ├── breadcrumb/       # Breadcrumb navigation
│   ├── dropdown-menu/    # Dropdown menus
│   ├── form/             # Form components
│   ├── sidebar/          # Sidebar components
│   └── ...               # Other UI components
├── layout/               # Layout-specific components
│   ├── Header.vue
│   ├── Footer.vue
│   └── NavLink.vue
└── AppSidebar.vue       # Feature-specific components
```

### Naming Conventions (Mandatory)
- **FILE NAMES**: PascalCase for all Vue components (`NavUser.vue`, `AppSidebar.vue`)
- **COMPOUND NAMES**: Use descriptive compound names (`DropdownMenuItem`, `SidebarMenuButton`)
- **CONSISTENT PREFIXES**: Group related components with prefixes (`Sidebar*`, `Breadcrumb*`)
- **UI COMPONENTS**: Follow shadcn/ui naming patterns for consistency

### Component Composition Patterns
- **SCRIPT SETUP**: Always use `<script setup lang="ts">` pattern
- **IMPORTS**: Use `@/Components/ui/` aliases for imports
- **PROPS**: Define props with TypeScript interfaces for complex objects
- **COMPOSABLES**: Use Vue 3 composables for shared logic
- **SLOTS**: Use slots for flexible content composition when needed

### State Management Strategy
- **LOCAL STATE**: Keep component state local when possible using `ref()` and `reactive()`
- **COMPOSABLES**: Use composables for shared logic across components
- **PARENT-CHILD**: Use props down, events up pattern for communication
- **GLOBAL STATE**: Consider Pinia only for truly global state (user auth, theme, etc.)
- **FORM STATE**: Use vee-validate + Zod for form validation and state

### Form Handling Standards
- **VEE-VALIDATE**: Use vee-validate with Zod schemas for validation
- **FORM COMPONENTS**: Use `FormField`, `FormItem`, `FormMessage` pattern
- **VALIDATION**: Client-side validation with Zod, server-side validation with Laravel
- **ERROR HANDLING**: Display validation errors consistently using form components

### Component Communication
- **PROPS**: Use TypeScript interfaces for complex prop definitions
- **EVENTS**: Use `defineEmits()` for parent communication
- **PROVIDE/INJECT**: Use for deep component tree communication when needed
- **SLOTS**: Use named slots for flexible component composition

### Component Examples
```vue
<!-- Feature Component -->
<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/Components/ui/button'

interface User {
  name: string
  email: string
}

const props = defineProps<{
  user: User
}>()

const emit = defineEmits<{
  userUpdated: [user: User]
}>()

const userInitials = computed(() => {
  return props.user.name.split(' ').map(n => n[0]).join('')
})
</script>

<template>
  <div>
    <span>{{ userInitials }}</span>
    <Button @click="emit('userUpdated', user)">Update</Button>
  </div>
</template>
```

### Import Patterns
- **UI COMPONENTS**: `import { Button } from '@/Components/ui/button'`
- **LAYOUT COMPONENTS**: `import Header from '@/Components/layout/Header.vue'`
- **FEATURE COMPONENTS**: `import AppSidebar from '@/Components/AppSidebar.vue'`
- **ICONS**: `import { LogOut } from 'lucide-vue-next'`

### CSS/Styling
- **PRIMARY**: Use Tailwind CSS classes
- **COMPONENTS**: Follow shadcn/ui patterns with reka-ui
- **NO CUSTOM CSS**: Unless absolutely necessary
- **RESPONSIVE**: Mobile-first approach

## 🚀 Deployment Workflow - EXACT SEQUENCE

### Before Every Commit
1. `./vendor/bin/pint` - Format PHP
2. `./vendor/bin/phpstan` - Analyze PHP  
3. `npm run lint` - Lint JS/Vue
4. `composer test` - Run PHP tests
5. `npm run build` - Verify build
6. `git add .` - Stage changes
7. `git commit -m "type: description"` - Single line commit
8. Verify with `git status`

### Development Workflow
1. `sail up` - Start development servers
2. Make changes following patterns above
3. Test changes work locally
4. Run quality checks (see "Before Every Commit")
5. Commit with proper format
6. Create PR if needed

## 📁 Important Files - KNOW THESE

### Configuration
- `vite.config.js` - Frontend build configuration
- `docker-compose.yml` - Development environment
- `components.json` - shadcn/ui configuration
- `tailwind.config.js` - Tailwind CSS configuration

### Application Entry Points  
- `resources/js/app.ts` - Frontend entry point
- `app/Http/Middleware/HandleInertiaRequests.php` - Shared props
- `routes/web.php` - Application routes

### Layouts
- `resources/js/Layouts/AuthenticatedLayout.vue` - Logged-in users
- `resources/js/Layouts/GuestLayout.vue` - Anonymous users

## ⚡ Quick Reference

### Most Common Tasks
```bash
# Start development
sail up

# Before commit checklist
./vendor/bin/pint && ./vendor/bin/phpstan && npm run lint && composer test && npm run build

# Single line commit
git commit -m "feat: add new feature"

# Run E2E tests  
npm run test:e2e
```

### Component Usage
```vue
<!-- Use shadcn/ui components -->
<script setup lang="ts">
import { Button } from '@/Components/ui/button'
</script>

<template>
  <Button variant="default">Click me</Button>
</template>
```

## Development Notes
- Don't use `composer dev`, the project uses `sail` to run.

---

**REMEMBER**: These are not suggestions - they are requirements. Follow them exactly to maintain code quality and consistency.