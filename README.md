# 🍯 Sauce Base

**Laravel SaaS Boilerplate with VILT Stack**

> ⚠️ **Development Status**: This project is currently in active development and is **NOT production-ready**. APIs, features, and architecture may change significantly.

A modern Laravel SaaS starter kit built with the VILT stack (Vue, Inertia, Laravel, Tailwind) - your essential foundation for building scalable SaaS applications.

[![CI](https://github.com/roble/saucebase/actions/workflows/ci.yml/badge.svg)](https://github.com/roble/saucebase/actions/workflows/ci.yml)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Features

- **🔐 Authentication** - Multi-provider OAuth (Google, GitHub, Facebook)
- **👥 User Management** - Role-based permissions with admin panel
- **🎨 Modern UI** - shadcn/ui components with dark/light themes
- **📱 Responsive** - Mobile-first design with sidebar navigation
- **⚡ Developer Experience** - Hot reload, TypeScript, testing suite

## Tech Stack

- **Backend**: Laravel 12, PHP 8.4+, PostgreSQL, Redis
- **Frontend**: Vue 3, TypeScript, Inertia.js, Tailwind CSS 4
- **UI**: shadcn/ui components, Lucide icons
- **Development**: Docker, Vite, Pest (PHP testing), Playwright (E2E testing)

## Quick Start

### Prerequisites
- Docker
- Node.js 22+ and npm

### Setup

1. **Clone and setup**
   ```bash
   git clone https://github.com/roble/saucebase.git
   cd saucebase
   chmod +x bin/setup-env
   ./bin/setup-env
   ```

2. **Start development**
   ```bash
   docker compose exec workspace npm run dev
   ```

3. **Visit** https://localhost or http://localhost

### Manual Setup
```bash
# Environment
cp .env.example .env

# Docker services
docker compose up -d

# Dependencies and database
docker compose exec workspace composer install
docker compose exec workspace php artisan key:generate
docker compose exec workspace php artisan migrate:fresh --seed

# Frontend
npm install && npm run build
```

## Development

```bash
# Start development server
docker compose exec workspace npm run dev

# Code quality (run before commit)
docker compose exec workspace ./vendor/bin/pint
docker compose exec workspace npm run lint
docker compose exec workspace composer test

# Fresh database
docker compose exec workspace php artisan migrate:fresh --seed
```

## Test Users

- **Admin**: `admin@saucebase.dev` / `password`
- **User**: `user@saucebase.dev` / `password`

## Contributing

1. Read `CLAUDE.md` for development guidelines
2. Run quality checks before committing
3. Follow conventional commit format: `feat: description`

## License

MIT License - see [LICENSE](LICENSE) file.

---

⭐ Star us on GitHub if this project helped you!