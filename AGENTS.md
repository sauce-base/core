## AGENTS.md — Source of truth for AI agents and project automation

This file is the canonical source for AI agents and automation tools interacting with this repository. It supersedes any other agent-specific docs (for example, `CLAUDE.md`) and should be consulted first.

Quick context

- Stack: Laravel 12 (PHP >= 8.4), Vue 3 + TypeScript, Inertia, Tailwind, Vite (VILT stack).
- Architecture: modular design using `nwidart/laravel-modules`. Modules live under `modules/<ModuleName>/` and are merged via composer merge-plugin and `module-loader.js`.

Key locations & conventions

- Modules: `modules/<ModuleName>/` — module `app/`, `resources/`, `routes/`, and `composer.json` may exist and get merged.
- Enable/disable modules: `modules_statuses.json` toggles modules on/off.
- Frontend: `resources/js/` (layouts, pages, composables). Frontend folders are lowercase (`components`, `pages`, `layouts`, `composables`) — follow this convention.
- i18n: Use `$t('key')` for all user-facing strings. Add Portuguese translations to `lang/pt_BR.json` when adding UI text.

Developer workflows (authoritative commands)

- Bootstrap (recommended):
    - `./bin/setup-env` — prepares Docker, SSL certs (if mkcert present), installs deps, seeds DB.
- Dev services:
    - `docker compose up -d` — spin Docker services (DB, Redis, Mailpit, etc.).
    - Composer full-stack dev: `docker compose exec workspace composer dev` — runs php server, queue worker, pail, and `npm run dev` concurrently.
    - For php artisan commands: `docker compose exec workspace php artisan <command>`.
- Frontend dev & build:
    - `npm install`
    - `npm run dev` — Vite dev server (hot reload)
    - `npm run build` — production build (includes SSR build)
- Testing:
    - Backend/unit: `docker compose exec workspace composer test` (runs `php artisan test`)
    - E2E: `npm run test` (Playwright)

Lint / format / static analysis

- PHP lint/format: `vendor/bin/pint` or `composer lint` (composer maps `lint` to pint)
- JS/TS lint: `npm run lint`
- Formatting (prettier): `npm run format` and `npm run format:check`
- Static analysis: `composer analyse` (runs `vendor/bin/phpstan analyse --memory-limit=2G`)

Commit & PR rules (enforced by tooling)

- Commit messages: Conventional commits enforced via `commitlint.config.js`.
    - Header max length: 150 characters.
    - Commits must be single-line (no body or footer).
    - Allowed types: `feat|fix|docs|style|refactor|perf|test|chore|ci|build|revert`.
    - Example: `feat: add OAuth callback`
    - Do not add co-authors or automated-attribution footers.
- Never commit secrets or `.env` files. Use environment/CI secrets instead.

Module rules

- To remove a module, use: `php artisan module:delete ModuleName` — do not `rm -rf` modules manually.
- If adding a module, update `modules_statuses.json` and follow the module structure (routes under `modules/<Module>/routes`, frontend resources under `modules/<Module>/resources`).

Files to inspect when answering code-change questions

- `modules_statuses.json`
- `module-loader.js`
- `composer.json` and `package.json` (scripts, dev dependencies, engines — Node >=22)
- `commitlint.config.js` (authoritative commit rules)
- `phpstan.neon`, `pint.json` (static analysis and formatting rules)

PR preflight checklist (run before opening a PR)

- Format & lint
    - JS/TS: `npm run format` && `npm run lint`
    - PHP: `vendor/bin/pint` or `composer lint`
- Tests
    - Backend/unit: `composer test`
    - E2E: `npm run test` (Playwright). Use `npm run test:e2e:headed` or `npm run test:e2e:ui` for local debugging.
- Static analysis & build
    - `composer analyse`
    - `npm run build` (if changing frontend/SSR)
- Module / i18n / infra checks
    - Update `modules_statuses.json` if adding/removing modules.
    - Ensure UI strings use `$t('key')` and add PT-BR translations to `lang/pt_BR.json` when needed.
    - If services are required, run `./bin/setup-env` or `docker compose up -d` before running tests.
- Commit & PR hygiene
    - Commit messages must follow `commitlint.config.js` (single-line header up to 150 chars).
    - PR description should include a brief "## Summary" and a short "How to test" section.
    - Confirm: lint passed, tests passed, static analysis passed, translations added (if UI text changed), module status updated (if modules changed).

Notes & maintenance

- This file is the single source of truth for AI agents and automation. When updating agent rules or workflows, update `AGENTS.md` and, optionally, `.github/copilot-instructions.md` as a shorter, GitHub-facing summary.
- If you want a PR template created from this checklist, say so and I will add `.github/pull_request_template.md`.

End of AGENTS.md
