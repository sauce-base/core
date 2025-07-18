# 🚀 Tadone

**Modern Laravel SaaS Starter Kit**

A production-ready foundation for building scalable SaaS applications with Laravel and Vue.js.

[![Build Status](https://github.com/roble/tadone/workflows/tests/badge.svg)](https://github.com/roble/tadone/actions)
[![License](https://img.shields.io/github/license/roble/tadone)](LICENSE)

## Features

- **🔐 Multi-Provider OAuth** - Google, GitHub, Facebook authentication
- **🎨 Modern UI** - shadcn/ui components with dark/light themes
- **📱 Responsive Design** - Mobile-first sidebar navigation
- **🧪 Full Testing Suite** - PHP (Pest) and E2E (Playwright) tests
- **⚡ Developer Experience** - Hot reload, TypeScript, code quality tools

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+, PostgreSQL, Redis
- **Frontend**: Vue 3, TypeScript, Inertia.js, Tailwind CSS
- **UI**: shadcn/ui, reka-ui, Lucide Icons
- **Testing**: Pest PHP, Playwright E2E
- **Development**: Docker, Laravel Sail

## Quick Start

1. **Clone and install**
   ```bash
   git clone https://github.com/roble/tadone.git
   cd tadone
   composer install && npm install
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Start development**
   ```bash
   sail up
   ```

4. **Visit**: http://localhost

## Development Commands

```bash
# Start development
sail up

# Code quality (run before commit)
./vendor/bin/pint && npm run lint && composer test && npm run build

# Testing
composer test        # PHP tests
npm run test:e2e    # E2E tests

# Database
sail artisan migrate --seed
```

## Project Structure

```
tadone/
├── app/                 # Laravel application
├── resources/js/        # Vue.js frontend
│   ├── Components/      # Reusable components
│   ├── Pages/          # Inertia.js pages
│   └── Layouts/        # Vue layouts
├── tests/              # PHP & E2E tests
└── CLAUDE.md          # Development guidelines
```

## Configuration

### OAuth Setup

Configure your OAuth providers in `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret

# GitHub OAuth  
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_client_id
FACEBOOK_CLIENT_SECRET=your_client_secret
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/name`
3. Run quality checks: `./vendor/bin/pint && npm run lint && composer test`
4. Commit: `git commit -m "feat: description"`
5. Push and create a Pull Request

## License

MIT License - see [LICENSE](LICENSE) file.

## Support

- **Issues**: [GitHub Issues](https://github.com/roble/tadone/issues)
- **Documentation**: See `CLAUDE.md` for development guidelines

---

⭐ Star us on GitHub if this project helped you!