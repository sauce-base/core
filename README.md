<p align="center">
  <img src="public/images/logo.svg" alt="Saucebase logo" width="250">
</p>

# Saucebase

**Modular Laravel SaaS Starter Kit**

> ⚠️ **Active development** – APIs, features, and architecture may change without notice.

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
[![Filament](https://img.shields.io/badge/Filament-4.0-10B981?logo=filament&logoColor=white)](https://filamentphp.com)
[![Playwright](https://img.shields.io/badge/Playwright-1.40-000000?logo=playwright&logoColor=white)](https://playwright.dev)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)](https://phpstan.org)
[![Commitlint](https://img.shields.io/badge/commitlint-enabled-brightgreen.svg?style=flat)](https://commitlint.js.org)

Sauce Base is a batteries-included Laravel starter kit built around the **VILT stack** (Vue, Inertia, Laravel, Tailwind). It embraces a modular architecture so you can
**install, copy, and own** feature packs—just like shadcn/ui—without inheriting hidden dependencies. Start from a solid core, pick the modules you need, and ship your SaaS
faster.

---

## 🚀 Why Sauce Base?

- **Modern Foundations**: Laravel 12, PHP 8.4+, Vue 3, TypeScript, Tailwind CSS 4, Vite 6.
- **First-Class Modules**: Installable feature modules (Auth, Localization, …) that you can copy, customize, and own forever.
- **Full-Stack DX**: Inertia.js SPA experience, Pinia stores, Ziggy routes, persistent state, i18n, built-in dark/light mode and theme management.
- **Headless Admin**: Filament 4 panel already wired.

---

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

---

### Prerequisites

- Docker
- Node.js 22+ and npm

## 🧱 Architecture Overview

```
├── app/                  # Core Laravel application (service providers, models, listeners)
├── modules/              # Feature modules (Auth, Localization, …) – install, copy, own
│   └── <ModuleName>/
│       ├── app/          # Module controllers, actions, providers
│       ├── resources/    # Vue pages/components, CSS, translations
│       └── routes/       # Module routes (web + api)
├── resources/js/         # Inertia SPA (layouts, pages, Pinia stores, middleware, UI lib)
├── database/             # Migrations, factories, seeders (roles & demo users included)
├── docker/               # Development Dockerfiles + configs
└── module-loader.js      # Collects enabled module assets and settings automatically
```

- Modules are managed with **nwidart/laravel-modules** and automatically discovered if marked `true` in `modules_statuses.json`.
- The SPA loads module pages with the namespace syntax (e.g. `Auth::Login`) so copied modules stay self-contained.
- Tailwind is configured via the new V4 workflow with shadcn-compatible component structure (`resources/js/components/...`).

---

## 📦 Modules to be installed

| Module         | Highlights                                                                                                       |
| -------------- | ---------------------------------------------------------------------------------------------------------------- |
| **Roles**      | Role management with Spatie permissions integration. [View module →](https://github.com/sauce-base/roles)        |
| **Auth**       | Authentication module for Laravel with social login support. [View module →](https://github.com/sauce-base/auth) |
| **Dashboard**  | Dashboard basic structure [View module →](https://github.com/sauce-base/dashboard)                               |
| **Navigation** | Navigation module for managing menus and links [View module →](https://github.com/sauce-base/navigation)         |
| **Settings**   | Settings management module [View module →](https://github.com/sauce-base/settings)                               |

### Copy-and-Own Philosophy

Upcoming modules will follow the same copy-and-own philosophy—pull the files you need, keep them in your repo, iterate freely.

---

## ⚙️ Quick Start

1. **Clone & bootstrap**

    ```bash
    git clone https://github.com/sauce-base/core.git
    cd core
    chmod +x bin/setup-env
    ./bin/setup-env
    ```

    The script checks prerequisites, prepares SSL certs (if mkcert is present), spins up Docker, runs migrations/seeds, installs JS/PHP deps.

2. **Visit the app**
    - Site: https://localhost (self-signed cert) or http://localhost
    - Filament Admin: https://localhost/admin

3. **Add Auth Module**§

Install the Auth module for authentication features:\*\*\*

`composer require saucebase/auth`

Run composer dump-autoload:

`composer dump-autoload`

Enable the module:

`docker compose exec workspace php artisan module:enable Auth`

Run migrations for the module:

`docker compose exec workspace php artisan module:migrate Auth`

Build the frontend assets:

`npm run build`

Check out the [Auth Module README](https://github.com/sauce-base/auth) for configuration details.

Complete documentation coming soon!

## 📄 License

MIT © Sauce Base. See [LICENSE](LICENSE).

---
