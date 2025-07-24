# CLAUDE.md - Development Guidelines

**CRITICAL**: This file contains mandatory guidelines that Claude Code MUST follow exactly. No exceptions.

## 🚀 Project Overview

**Sauce Base** is a modern Laravel SaaS starter kit designed for rapid development of scalable SaaS applications. Built with the VILT stack (Vue, Inertia, Laravel, Tailwind), it provides the essential foundation - your "SaaS Base" - for building and launching SaaS products quickly.

### Key Features
- **Modern Stack**: Laravel 12 + Vue 3 + TypeScript + Inertia.js + Tailwind CSS
- **Authentication**: Multi-provider social login (Google, GitHub, Facebook)
- **Components**: shadcn/ui with reka-ui implementation
- **Testing**: Comprehensive PHP (Pest) and E2E (Playwright) testing
- **Developer Experience**: Hot reload, TypeScript, ESLint, PHP CS Fixer, SSL-enabled local development
- **Production Ready**: Docker, Redis, PostgreSQL, optimized build pipeline

### Development Requirements
- **Node.js**: v22.0.0+ (LTS, supported until April 2027)
- **npm**: v10.5.1+ (bundled with Node.js v22)
- **PHP**: 8.2+ (Laravel 12 requirement)
- **Docker**: Required for development environment

## 🚨 MANDATORY RULES - NEVER VIOLATE THESE

### Security - CRITICAL REQUIREMENTS
- **NEVER COMMIT SECRETS**: No API keys, passwords, tokens, or sensitive data in git
- **NEVER EXPOSE SECRETS**: No secrets in logs, error messages, or client-side code
- **USE .env FILES**: All sensitive config goes in .env (never committed)
- **VERIFY .gitignore**: Ensure .env, keys, certificates are properly ignored
- **AUDIT COMMITS**: Review all commits for accidental secret exposure

### Git Commits - STRICT ENFORCEMENT
- **FORMAT**: Single line only, no body, no multi-line messages
- **NO AUTHORS**: Never add Co-Authored-By, Generated with Claude, or any attribution
- **PATTERN**: `type: description` (e.g., `feat: add user authentication`)
- **GROUPING**: Logical changes together, infrastructure separate from features
- **VERIFICATION**: Always run `git status` after commit to verify success
- **BRANCH MANAGEMENT**: Always create/switch to a branch when working in a GitHub issue
- **NO CO-AUTHORS**: Never add co-author to PR or issues ever.
- **COMMIT MESSAGE LENGTH**: Commits messages has to have less than 72 characters.

### Pull Requests - MANDATORY FORMAT  
- **TITLE**: Clear, descriptive summary
- **BODY**: Must include "## Summary" section
- **NO ATTRIBUTION**: Never add "Generated with Claude" or similar

### Code Quality - ALWAYS RUN THESE
- **BEFORE COMMIT**: Always run `./vendor/bin/pint` and `npm run lint`
- **TESTS**: Run `composer test` after backend changes
- **BUILD**: Run `npm run build` after frontend changes
- **VERIFY**: Check all commands pass before committing

## 📁 Directory Structure Guidelines

### Frontend Architecture
- **LOWERCASE DIRECTORIES**: All frontend directories use lowercase naming (`components`, `composables`, `pages`, `layouts`)
- **SHADCN COMPATIBILITY**: Lowercase structure ensures compatibility with shadcn/ui component library
- **CROSS-PLATFORM**: Avoids case sensitivity issues across different operating systems
- **MODERN CONVENTIONS**: Follows contemporary JavaScript/Vue.js project standards

### Required Directory Structure
```
resources/js/
├── components/          # Vue components (lowercase)
├── composables/         # Vue composition functions (lowercase)  
├── pages/              # Inertia.js pages (lowercase)
├── layouts/            # Vue layouts (lowercase)
├── lib/                # Utilities and helpers
└── validation/         # Zod validation schemas
```

## 📝 Development Memories

### Git Workflow
- **Group commits by concern**: When making multiple related changes, group them into a single commit to maintain a clean and logical commit history