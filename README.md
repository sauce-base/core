# 🍯 Sauce Base

**Modern Laravel SaaS Starter Kit**

> ⚠️ **Development Status**: This project is currently in active development and is **NOT production-ready**. APIs, features, and architecture may change significantly. Use at your own risk.

The essential foundation - your "SaaS Base" - for building scalable SaaS applications with Laravel and Vue.js, designed for rapid development and prototyping.

[![CI](https://github.com/roble/saucebase/actions/workflows/ci.yml/badge.svg)](https://github.com/roble/saucebase/actions/workflows/ci.yml)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.4-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.8-3178C6?logo=typescript&logoColor=white)](https://typescriptlang.org)
[![Vite](https://img.shields.io/badge/Vite-6.2-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.1-06B6D4?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Node.js](https://img.shields.io/badge/Node.js-22.0%2B-339933?logo=node.js&logoColor=white)](https://nodejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?logo=inertia&logoColor=white)](https://inertiajs.com)

## Features

- **🔐 Multi-Provider OAuth** - Google, GitHub, Facebook authentication
- **🎨 Modern UI** - shadcn/ui components with dark/light themes
- **📱 Responsive Design** - Mobile-first sidebar navigation
- **🧪 Full Testing Suite** - PHP (Pest) and E2E (Playwright) tests
- **⚡ Developer Experience** - Hot reload, TypeScript, code quality tools

## Tech Stack

### Backend
- **[Laravel 12](https://laravel.com/docs)** - PHP web framework
- **[PHP 8.4+](https://www.php.net/docs.php)** - Programming language
- **[PostgreSQL](https://www.postgresql.org/docs/)** - Database
- **[Redis](https://redis.io/docs/)** - Caching & session storage
- **[Laravel Sanctum](https://laravel.com/docs/sanctum)** - API authentication
- **[Laravel Socialite](https://laravel.com/docs/socialite)** - OAuth providers
- **[Laravel Horizon](https://laravel.com/docs/horizon)** - Queue monitoring
- **[Laravel Telescope](https://laravel.com/docs/telescope)** - Debug assistant

### Frontend
- **[Vue 3](https://vuejs.org/)** - JavaScript framework
- **[TypeScript](https://www.typescriptlang.org/docs/)** - Type safety
- **[Inertia.js](https://inertiajs.com/)** - SPA without API
- **[Tailwind CSS 4](https://tailwindcss.com/docs)** - Utility-first CSS
- **[shadcn/ui](https://www.shadcn-vue.com/)** - UI component library
- **[reka-ui](https://www.reka-ui.com/)** - Vue primitive components
- **[Lucide Icons](https://lucide.dev/)** - Icon library
- **[VeeValidate](https://vee-validate.logaretm.com/)** - Form validation
- **[Zod](https://zod.dev/)** - Schema validation
- **[VueUse](https://vueuse.org/)** - Vue composition utilities

### Development & Testing
- **[Docker](https://docs.docker.com/)** - Containerization
- **[Laravel Sail](https://laravel.com/docs/sail)** - Docker development environment
- **[Vite](https://vitejs.dev/)** - Frontend build tool
- **[Pest](https://pestphp.com/)** - PHP testing framework
- **[Playwright](https://playwright.dev/)** - End-to-end testing
- **[Laravel Pint](https://laravel.com/docs/pint)** - PHP code formatting
- **[PHPStan](https://phpstan.org/)** - PHP static analysis
- **[ESLint](https://eslint.org/)** - JavaScript linting
- **[Husky](https://typicode.github.io/husky/)** - Git hooks
- **[mkcert](https://github.com/FiloSottile/mkcert)** - Local SSL certificates

## Quick Start

### Prerequisites
- **[Docker](https://docs.docker.com/get-docker/)** - Required for containerized development
- **[mkcert](https://github.com/FiloSottile/mkcert)** - Optional, for trusted SSL certificates
- **Node.js 22+** (LTS) and **npm** - For local asset building

### Automated Setup (Recommended)

1. **Clone the repository**
   ```bash
   git clone https://github.com/roble/saucebase.git
   cd saucebase
   ```

2. **Run automated setup**
   ```bash
   chmod +x bin/setup-env
   ./bin/setup-env
   ```
   
   This script will:
   - Check system requirements
   - Set up Docker environment
   - Configure SSL certificates (with mkcert)
   - Install dependencies
   - Run database migrations and seeders
   - Build frontend assets

3. **Start development server**
   ```bash
   docker compose exec workspace npm run dev
   ```

4. **Visit your application**
   - With SSL: https://localhost
   - Without SSL: http://localhost

### Manual Setup

If you prefer manual setup or the automated script fails:

1. **Clone and prepare environment**
   ```bash
   git clone https://github.com/roble/saucebase.git
   cd saucebase
   cp .env.example .env
   ```

2. **Start Docker services**
   ```bash
   docker compose up -d
   ```

3. **Install dependencies and setup database**
   ```bash
   docker compose exec workspace composer install
   docker compose exec workspace php artisan key:generate
   docker compose exec workspace php artisan migrate:fresh --seed
   ```

4. **Build frontend assets**
   ```bash
   npm install && npm run build
   ```

## Development Commands

### Starting Development
```bash
# Start all Docker services
docker compose up -d

# Start frontend development server (with hot reload)
docker compose exec workspace npm run dev

# Monitor background queues
docker compose exec workspace php artisan horizon
```

### Code Quality (Required Before Commit)
```bash
# Format PHP code
docker compose exec workspace ./vendor/bin/pint

# Static analysis
docker compose exec workspace ./vendor/bin/phpstan

# Lint JavaScript/Vue
docker compose exec workspace npm run lint

# Run PHP tests
docker compose exec workspace composer test

# Build frontend assets
docker compose exec workspace npm run build

# Run all quality checks at once
docker compose exec workspace ./vendor/bin/pint && \
docker compose exec workspace ./vendor/bin/phpstan && \
docker compose exec workspace npm run lint && \
docker compose exec workspace composer test && \
docker compose exec workspace npm run build
```

### Database Management
```bash
# Fresh migration with seeding
docker compose exec workspace php artisan migrate:fresh --seed

# Regular migration
docker compose exec workspace php artisan migrate

# Seed database
docker compose exec workspace php artisan db:seed
```

### Testing
```bash
# PHP unit/feature tests
docker compose exec workspace composer test

# E2E tests (headless)
docker compose exec workspace npm run test:e2e

# E2E tests (with browser)
docker compose exec workspace npm run test:e2e:headed

# E2E debug mode
docker compose exec workspace npm run test:e2e:debug
```

### Utility Commands
```bash
# Clear application caches
docker compose exec workspace php artisan optimize:clear

# Generate app key
docker compose exec workspace php artisan key:generate

# View logs
docker compose exec workspace php artisan pail
```

## Project Structure

```
saucebase/
├── app/                          # Laravel Backend
│   ├── Actions/                  # Business logic actions
│   ├── Http/Controllers/         # Controllers
│   ├── Models/                   # Eloquent models
│   └── Providers/                # Service providers
├── resources/                    # Frontend & Views
│   ├── js/                       # Vue.js application
│   │   ├── Components/           # Reusable Vue components
│   │   │   ├── ui/              # shadcn/ui components
│   │   │   └── layout/          # Layout components
│   │   ├── Pages/               # Inertia.js pages
│   │   ├── Layouts/             # Vue layouts
│   │   ├── Composables/         # Vue composition functions
│   │   ├── lib/                 # Utilities and helpers
│   │   └── validation/          # Zod validation schemas
│   ├── css/                     # Stylesheets
│   └── views/                   # Blade templates
├── tests/                       # Testing
│   ├── Feature/                 # Laravel feature tests
│   ├── Unit/                    # Laravel unit tests
│   └── e2e/                     # Playwright E2E tests
├── database/                    # Database
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories
├── docker/                      # Docker configuration
│   ├── development/             # Development containers
│   └── production/              # Production containers
├── config/                      # Laravel configuration
├── routes/                      # Application routes
├── public/                      # Public assets
├── storage/                     # File storage
├── bin/setup-env               # Environment setup script
├── CLAUDE.md                   # Development guidelines (READ THIS!)
├── docker-compose.yml          # Docker development setup
├── package.json                # Node.js dependencies
├── composer.json               # PHP dependencies
├── vite.config.js             # Frontend build configuration
├── tailwind.config.js         # Tailwind CSS configuration
├── components.json            # shadcn/ui configuration
├── playwright.config.ts       # E2E test configuration
└── phpunit.xml                # PHP test configuration
```

### Key Configuration Files
- **`CLAUDE.md`** - Comprehensive development guidelines and standards
- **`docker-compose.yml`** - Docker development environment
- **`components.json`** - shadcn/ui component configuration  
- **`vite.config.js`** - Frontend build and development server
- **`playwright.config.ts`** - End-to-end testing configuration
- **`bin/setup-env`** - Automated environment setup script

## Configuration

### Environment Setup

Copy `.env.example` to `.env` and configure your environment:

```env
# Application
APP_NAME="Sauce Base"
APP_ENV=local
APP_KEY=                    # Generated automatically
APP_DEBUG=true
APP_URL=https://localhost
APP_HOST=localhost

# Database
DB_CONNECTION=pgsql
DB_HOST=postgresql
DB_PORT=5432
DB_DATABASE=saucebase
DB_USERNAME=saucebase
DB_PASSWORD=password

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### SSL Configuration

For HTTPS development with trusted certificates:

1. **Install mkcert**
   ```bash
   # macOS
   brew install mkcert
   
   # Windows
   choco install mkcert
   
   # Linux
   curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64"
   chmod +x mkcert-v*-linux-amd64
   sudo cp mkcert-v*-linux-amd64 /usr/local/bin/mkcert
   ```

2. **Install CA in system trust store**
   ```bash
   mkcert -install
   ```

3. **Generate certificates** (done automatically by `bin/setup-env`)
   ```bash
   cd docker/development/ssl
   mkcert localhost 127.0.0.1 ::1
   ```

### OAuth Providers

Configure social authentication in `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret

# GitHub OAuth  
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
```

**OAuth Setup Instructions:**
1. **Google**: [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. **GitHub**: [GitHub Developer Settings](https://github.com/settings/developers)
3. **Facebook**: [Meta for Developers](https://developers.facebook.com/apps/)

## Testing

### PHP Testing (Pest)

Run PHP unit and feature tests:

```bash
# Run all PHP tests
docker compose exec workspace composer test

# Run specific test file
docker compose exec workspace php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run with coverage
docker compose exec workspace composer test -- --coverage
```

### End-to-End Testing (Playwright)

**Prerequisites**: Start the application first:
```bash
docker compose up -d
```

**Run E2E tests:**
```bash
# Headless mode (CI/automated)
docker compose exec workspace npm run test:e2e

# Visual mode (debugging)
docker compose exec workspace npm run test:e2e:headed

# Debug mode (step through)
docker compose exec workspace npm run test:e2e:debug

# Generate test report
docker compose exec workspace npm run test:e2e:report
```

**Test Structure:**
- `tests/e2e/` - E2E test files
- `tests/e2e/pages/` - Page Object Models
- `tests/e2e/fixtures/` - Test data
- `tests/e2e/utils/` - Helper functions

### Test Data

Default seeded users for testing:
- **Admin**: `admin@saucebase.dev` / `password`
- **User**: `user@saucebase.dev` / `password`

## Contributing

**⚠️ Development Status**: This project is in active development. Contribution guidelines may change.

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/name`  
3. **Read `CLAUDE.md`** for development standards
4. Run quality checks:
   ```bash
   docker compose exec workspace ./vendor/bin/pint
   docker compose exec workspace ./vendor/bin/phpstan  
   docker compose exec workspace npm run lint
   docker compose exec workspace composer test
   docker compose exec workspace npm run build
   ```
5. Commit with conventional format: `git commit -m "feat: description"`
6. Push and create a Pull Request

**Required:**
- All tests must pass
- Code must be formatted with Pint and ESLint
- Follow patterns established in `CLAUDE.md`

## License

MIT License - see [LICENSE](LICENSE) file.

## Development Resources

### Documentation
- **`CLAUDE.md`** - Complete development guidelines and standards
- **Laravel Docs**: https://laravel.com/docs
- **Vue.js Guide**: https://vuejs.org/guide/
- **Inertia.js Docs**: https://inertiajs.com/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **shadcn/ui**: https://www.shadcn-vue.com/

### Troubleshooting

**Common Issues:**

1. **Docker containers won't start**
   ```bash
   docker compose down -v
   docker compose up -d --build
   ```

2. **Frontend not updating**
   ```bash
   docker compose exec workspace npm run dev
   # Visit http://localhost:5173 instead of https://localhost
   ```

3. **Database connection errors**
   ```bash
   docker compose exec workspace php artisan migrate:fresh --seed
   ```

4. **Permission errors**
   ```bash
   sudo chown -R $USER:$USER .
   chmod +x bin/setup-env
   ```

### Support

- **Issues**: [GitHub Issues](https://github.com/roble/saucebase/issues)
- **Development Guidelines**: Read `CLAUDE.md` (mandatory for contributors)
- **Docker Issues**: Ensure Docker daemon is running and try `--force-build`

---

> **Disclaimer**: This is a development project. Use at your own risk in production environments.

⭐ Star us on GitHub if this project helped you!