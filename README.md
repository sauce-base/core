<p align="center">
  <img src="public/images/logo.svg" alt="Saucebase logo" width="250">
</p>

# Saucebase

**Modular Laravel SaaS Starter Kit**

> ⚠️ **Active development** – APIs, features, and architecture may change without notice.

[![PHPUnit Tests](https://github.com/sauce-base/saucebase/actions/workflows/phpunit.yml/badge.svg)](https://github.com/sauce-base/saucebase/actions/workflows/phpunit.yml)
[![E2E Tests](https://github.com/sauce-base/saucebase/actions/workflows/e2e.yml/badge.svg)](https://github.com/sauce-base/saucebase/actions/workflows/e2e.yml)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.4-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.8-3178C6?logo=typescript&logoColor=white)](https://typescriptlang.org)
[![Vite](https://img.shields.io/badge/Vite-6.2-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.1-06B6D4?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Node.js](https://img.shields.io/badge/Node.js-22.0%2B-339933?logo=node.js&logoColor=white)](https://nodejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?logo=inertia&logoColor=white)](https://inertiajs.com)
[![Filament](https://img.shields.io/badge/Filament-4.0-10B981?logo=filament&logoColor=white)](https://filamentphp.com)
[![Playwright](https://img.shields.io/badge/Playwright-1.40-000000?logo=playwright&logoColor=white)](https://playwright.dev)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)](https://phpstan.org)
[![Commitlint](https://img.shields.io/badge/commitlint-enabled-brightgreen.svg?style=flat)](https://commitlint.js.org)

Saucebase is a **Laravel SaaS boilerplate / Starter kit** that lets you own your code. Built on the **VILT stack** (Vue 3, Inertia.js, Laravel, Tailwind CSS), it follows the **copy-and-own philosophy** pioneered by shadcn/ui—install feature modules directly into your repository, customize freely, and never worry about upstream breaking changes.

Start with a minimal, production-ready core. Add pre-built modules (Auth, Settings) with one command. Everything lives in your repo. No hidden packages, no vendor lock-in. Just modern Laravel development with TypeScript, hot reload, Docker-first setup, and built-in best practices.

📖 **[Full Documentation →](https://sauce-base.github.io/docs/)**

---

## 🚀 Why Saucebase?

### You Own The Code

Like shadcn/ui, modules install **directly into your repository**. No vendor packages to break. Customize, refactor, or completely rewrite any feature without forking or maintaining patches. Your codebase, your rules.

### Built for Speed

- **Docker-first**: One command (`php artisan saucebase:install`) launches MySQL, Redis, Mailpit, generates SSL certs (with wildcard for multi-tenancy), runs migrations, and seeds
- **Hot reload**: Vite dev server with instant HMR for Vue/TypeScript/CSS changes
- **Type-safe routes**: Ziggy generates TypeScript route helpers from Laravel routes
- **Pre-configured modules**: Auth and Settings ready to install

### Modern Stack, Zero Compromises

- **Frontend**: Vue 3 Composition API, TypeScript 5.8, Inertia.js 2.0, Tailwind CSS 4, VueUse composables
- **Backend**: Laravel 12, PHP 8.4+, Filament 4 admin panel, Spatie permissions, Laravel Horizon
- **DX Tools**: PHPStan level 9, Pint formatter, ESLint, Prettier, Playwright E2E tests, Commitlint

### Production-Ready Defaults

Built-in i18n (Portuguese + English), persistent dark/light mode, SSR support, Redis caching, queue workers, and email testing (Mailpit). Not just a starter—a foundation you can ship.

## 📸 Screenshots

### Home Page

**Light Theme**

![Home - Light Theme](public/images/screenshots/home-light.png)

**Dark Theme**

![Home - Dark Theme](public/images/screenshots/home-dark.png)

### Authentication

**Login Page - Light Theme**

![Login - Light Theme](public/images/screenshots/login-light.png)

**Login Page - Dark Theme**

![Login - Dark Theme](public/images/screenshots/login-dark.png)

**Register Page - Light Theme**

![Register - Light Theme](public/images/screenshots/register-light.png)

**Register Page - Dark Theme**

![Register - Dark Theme](public/images/screenshots/register-dark.png)

### Dashboard

**Light Theme**

![Dashboard - Light Theme](public/images/screenshots/dashboard-light.png)

**Dark Theme**

![Dashboard - Dark Theme](public/images/screenshots/dashboard-dark.png)

### Settings

**Light Theme**

![Settings - Light Theme](public/images/screenshots/settings-light.png)

**Dark Theme**

![Settings - Dark Theme](public/images/screenshots/settings-dark.png)

### Profile Settings

**Light Theme**

![Profile Settings - Light Theme](public/images/screenshots/profile-light.png)

![Profile Edit - Light Theme](public/images/screenshots/profile-edit-light.png)

**Dark Theme**

![Profile Settings - Dark Theme](public/images/screenshots/profile-dark.png)

![Profile Edit - Dark Theme](public/images/screenshots/profile-edit-dark.png)

---

## ⚙️ Quick Start

```bash
composer create-project saucebase/saucebase my-app
cd my-app
php artisan saucebase:install
npm run dev
```

That's it! Visit **https://localhost** to see your app.

### Requirements

- Docker Desktop
- Node.js 18+
- npm 8+

> **Optional:** Install [mkcert](https://github.com/FiloSottile/mkcert) for HTTPS support

### What Gets Installed

The installer sets up Docker containers (MySQL, Redis, Nginx), generates SSL certificates, runs migrations, installs required modules (Auth, Settings) via composer, and builds frontend assets. Answer a few prompts and you're ready to code.

---

## 📚 Documentation

For detailed guides, architecture overviews, module documentation, and more:

**[→ View Full Documentation](https://sauce-base.github.io/docs/)**

Key topics covered:

- [Installation Guide](https://sauce-base.github.io/docs/getting-started/installation)
- [Architecture Overview](https://sauce-base.github.io/docs/architecture/overview)
- [Working with Modules](https://sauce-base.github.io/docs/fundamentals/modules)
- [Development Commands](https://sauce-base.github.io/docs/development/commands)
- [Coding Standards](https://sauce-base.github.io/docs/development/coding-standards)
- [Troubleshooting Guide](https://sauce-base.github.io/docs/reference/troubleshooting)

---

## 📦 Available Modules

| Module       | Description                                                                                                      |
| ------------ | ---------------------------------------------------------------------------------------------------------------- |
| **Auth**     | Authentication module for Laravel with social login support. [View module →](https://github.com/sauce-base/auth) |
| **Settings** | Settings management module [View module →](https://github.com/sauce-base/settings)                               |

More modules coming soon following the copy-and-own philosophy.

---

## 📄 License

MIT © Sauce Base. See [LICENSE](LICENSE).

---
