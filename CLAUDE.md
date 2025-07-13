# CLAUDE.md - Development Guidelines

**CRITICAL**: This file contains mandatory guidelines that Claude Code MUST follow exactly. No exceptions.

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
composer dev  # Starts Laravel + Vite + queue worker + logs

# Quality checks (run before commit)
./vendor/bin/pint     # PHP formatting
./vendor/bin/phpstan  # PHP static analysis  
npm run lint          # JS/Vue linting
composer test         # PHP tests
npm run build         # Production build test
```

### Backend (Laravel/PHP)
- `composer dev` - **MAIN COMMAND**: Starts everything (Laravel + Vite + workers)
- `composer test` - Run all PHP tests
- `php artisan test --filter=TestName` - Run specific test
- `./vendor/bin/pint` - **REQUIRED**: Format PHP code
- `./vendor/bin/phpstan` - **REQUIRED**: Static analysis
- `php artisan migrate` - Database migrations
- `php artisan db:seed` - Seed database
- `php artisan optimize:clear` - Clear all caches

### Frontend (Vue/TypeScript)
- `npm run dev` - Vite dev server (usually run via `composer dev`)
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
- **BEFORE TESTS**: Must run `composer dev` first
- **EXECUTION**: Use `npm run test:e2e` for results

### PHP Testing (Pest)
- **RUN**: `composer test` before every commit
- **LOCATION**: `tests/Feature/` and `tests/Unit/`
- **DATABASE**: Use `RefreshDatabase` trait

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
1. `composer dev` - Start development servers
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
composer dev

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

---

**REMEMBER**: These are not suggestions - they are requirements. Follow them exactly to maintain code quality and consistency.
```