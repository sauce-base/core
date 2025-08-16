# 🍯 Sauce Base

**Laravel SaaS Boilerplate with VILT Stack**

> ⚠️ **Development Status**: This project is currently in active development and is **NOT production-ready**. APIs, features, and architecture may change significantly.

A modern Laravel SaaS starter kit built with the VILT stack (Vue, Inertia, Laravel, Tailwind) - your essential foundation for building scalable SaaS applications.

[![CI](https://github.com/sauce-base/core/actions/workflows/ci.yml/badge.svg)](https://github.com/sauce-base/core/actions/workflows/ci.yml)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.4-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.8-3178C6?logo=typescript&logoColor=white)](https://typescriptlang.org)
[![Vite](https://img.shields.io/badge/Vite-6.2-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.1-06B6D4?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Node.js](https://img.shields.io/badge/Node.js-22.0%2B-339933?logo=node.js&logoColor=white)](https://nodejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?logo=inertia&logoColor=white)](https://inertiajs.com)

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
   git clone https://github.com/sauce-base/core.git
   cd core
   chmod +x bin/setup-env
   ./bin/setup-env
   ```

2. **Start development**
   ```bash
   npm run dev
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
npm run dev

# Code quality (run before commit)
docker compose exec workspace ./vendor/bin/pint
docker compose exec workspace npm run lint
docker compose exec workspace composer test

# Fresh database
docker compose exec workspace php artisan migrate:fresh --seed
```

## Contributing

1. Read `CLAUDE.md` for development guidelines
2. Run quality checks before committing
3. Follow conventional commit format: `feat: description`

## License

MIT License - see [LICENSE](LICENSE) file.

---

⭐ Star us on GitHub if this project helped you!