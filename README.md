# 🍯 Sauce Base

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
[![Pest](https://img.shields.io/badge/Pest-2.0-FF4785?logo=pest&logoColor=white)](https://pestphp.com)
[![Playwright](https://img.shields.io/badge/Playwright-1.40-000000?logo=playwright&logoColor=white)](https://playwright.dev)

Sauce Base is a batteries-included Laravel starter kit built around the **VILT stack** (Vue, Inertia, Laravel, Tailwind). It embraces a modular architecture so you can
**install, copy, and own** feature packs—just like shadcn/ui—without inheriting hidden dependencies. Start from a solid core, pick the modules you need, and ship your SaaS
faster.

---

## 🚀 Why Sauce Base?

- **Modern Foundations**: Laravel 12, PHP 8.4+, Vue 3, TypeScript, Tailwind CSS 4, Vite 6.
- **First-Class Modules**: Installable feature modules (Auth, Localization, …) that you can copy, customize, and own forever.
- **Full-Stack DX**: Inertia.js SPA experience, Pinia stores, Ziggy routes, persistent state, i18n, built-in dark/light mode and theme management.
- **Production Ready**: Dockerized stack (Nginx, PHP-FPM, MySQL, Redis, Mailpit, Soketi), seeded roles/users, Playwright E2E tests, Pest unit tests.
- **Headless Admin**: Filament 4 panel already wired with module discovery via `Coolsam/Modules`.

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
└── vite-module-loader.js # Collects enabled module assets automatically for Vite
```

- Modules are managed with **nwidart/laravel-modules** and automatically discovered if marked `true` in `modules_statuses.json`.
- The SPA loads module pages with the namespace syntax (e.g. `Auth::Login`) so copied modules stay self-contained.
- Tailwind is configured via the new V4 workflow with shadcn-compatible component structure (`resources/js/components/...`).

---

## 📦 Included Modules

| Module           | Highlights                                                                                          |
| ---------------- | --------------------------------------------------------------------------------------------------- |
| **Auth**         | Inertia auth pages, Socialite (Google/GitHub/Facebook-ready), Pinia auth store, Social connections. |
| **Localization** | Language switcher (EN / PT-BR out of the box), session middleware, persisted store, dropdown UI.    |

Upcoming modules will follow the same copy-and-own philosophy—pull the files you need, keep them in your repo, iterate freely.

- The modules will be moved to a separate repo soon, along with a catalog of add-ons.

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

2. **Run development services**

    ```bash
    docker compose up -d
    npm run dev
    ```

3. **Visit the app**
    - Site: https://localhost (self-signed cert) or http://localhost
    - Filament Admin: https://localhost/admin

Complete documentation coming soon!

## 📄 License

MIT © Sauce Base. See [LICENSE](LICENSE).

---
