## Quick context for AI coding assistants

This repository is a modular Laravel 12 application (VILT stack: Vue 3, Inertia, Laravel, Tailwind) designed as a SaaS starter kit. The codebase uses a module pattern (see `modules/`) with `nwidart/laravel-modules` plus a custom `module-loader.js` and `modules_statuses.json` to enable/disable modules.

Keep answers focused, code-aware, and conservative: prefer small, incremental changes, follow existing conventions, and never introduce secrets or attribution text in commits or PRs.

- Key locations and patterns
- Module root: `modules/<ModuleName>/` — controllers, providers, views and module-specific composer.json are merged at runtime.
- Module enable/disable: `modules_statuses.json` (toggle a module to true/false).
- Module routes: `modules/<Module>/routes/web.php` or `routes/` under the module.
- Frontend SPA: `resources/js/` (layouts, pages, Pinia stores). Keep directory names lowercase (components, pages, layouts).
- Example service: `modules/Auth/app/Services/SocialiteService.php` demonstrates how module services live inside `modules/<Module>/app` and integrate with Laravel social login.

Developer workflows (exact commands)

- Bootstrap (recommended):
	- `./bin/setup-env` — prepares Docker, SSL certs (if mkcert present), installs deps, seeds DB.
- Dev services:
	- `docker compose up -d` — spin Docker services (DB, Redis, Mailpit, etc.).
	- Or run PHP & Vite locally via Composer script:
		- `composer dev` — runs php server, queue worker, pail, and `npm run dev` concurrently.
- Frontend dev & build:
	- `npm install`
	- `npm run dev` — Vite dev server (hot reload)
	- `npm run build` — production build (includes SSR build)
- Testing
	- Backend/unit: `composer test` (runs `php artisan test`)
	- E2E: `npm run test` (Playwright)
	- Playwright helpers: `npm run test:e2e:ui`, `npm run test:e2e:headed`, `npm run test:e2e:report`
- Linting / formatting / static analysis
	- PHP lint/format: `vendor/bin/pint` or `composer lint` (composer maps `lint` to pint)
	- JS/TS lint: `npm run lint` (runs ESLint against `./resources` and `modules/*/resources`)
	- Formatting (prettier): `npm run format` and `npm run format:check`
	- Static analysis: `composer analyse` (runs `vendor/bin/phpstan analyse --memory-limit=2G`)

Notes: many scripts are defined across `package.json` and `composer.json`; prefer using the composer script for full-stack dev (`composer dev`) when you want everything wired together.

Project-specific rules (discoverable and enforceable)

- Never commit secrets or `.env` contents. Sensitive config belongs in `.env` (gitignored).
- Commit message format: Conventional commits enforced via `commitlint.config.js`.
	- Header max length: `header-max-length` = 150 (use up to 150 chars).
	- Commits must be single-line: bodies and footers are rejected (rules `body-empty` and `footer-empty` enforce no body/footer).
	- Allowed types: `feat|fix|docs|style|refactor|perf|test|chore|ci|build|revert`.
	- Example: `feat: add OAuth callback` (single line, lowercase type)
	- Do not add co-authors or "Generated with ..." attributions in commits (footers are disallowed).
- Module removal: use `php artisan module:delete ModuleName` — do not manually `rm -rf` a module.
- i18n: All user-facing strings must use the i18n system (`$t('key')`); add Portuguese translations to `lang/pt_BR.json` when adding UI text.

What to change and how (practical examples)
- Add a module route: place route files in `modules/<Module>/routes` and register via the module service provider. Use the module namespace (e.g., `Auth::Login`).
- Add frontend components: put Vue pages under `modules/<Module>/resources/js/pages/` or `resources/js/pages/`; use lowercase folder names. Wire Pinia stores under `resources/js/stores` or module-specific stores under the module's resources.
- Run formatting and tests before proposing patches: `vendor/bin/pint` && `npm run lint` && `composer test` (PHP) / `npm run test` (E2E).

Files to inspect when answering code-change questions
- `modules_statuses.json` — module enablement
- `module-loader.js` — how module assets are collected
- `composer.json` and `package.json` — scripts, dev dependencies, engines (Node >=22)
- `phpstan.neon`, `pint.json` — static analysis & code style
- `tests/` and `playwright/` — test suites examples

When uncertain, prefer minimal, reverting changes

- Create small, focused commits that are easy to review and revert.
- If adding new dependencies, prefer dev-only and document why in the PR body.

If you need more details
- Ask for the preferred branch (default dev here) and whether to update module status files.
- If a task touches secrets, request a safe env-file or CI secret — do not put secrets in code or commits.

## PR preflight checklist (run before opening a PR)

Run these commands locally and verify the outcomes. Prefer the Composer `dev` script when you need the full stack running.

- Format & lint
	- JS/TS: `npm run format` && `npm run lint`
	- PHP: `vendor/bin/pint` or `composer lint`

- Tests
	- Backend/unit: `composer test` (runs `php artisan test`)
	- E2E: `npm run test` (Playwright). Use `npm run test:e2e:headed` or `npm run test:e2e:ui` for local debugging.

- Static analysis & build
	- Static analysis: `composer analyse` (phpstan)
	- Frontend build (if changing client code or SSR): `npm run build`

- Module / i18n / infra checks
	- If adding/removing modules, update `modules_statuses.json` and use `php artisan module:delete ModuleName` to remove modules safely.
	- Ensure all user-facing strings use `$t('key')` and add Portuguese translations to `lang/pt_BR.json` when needed.
	- If integration services are required, run `./bin/setup-env` or `docker compose up -d` before running tests.

- Commit and PR hygiene
	- Commit messages must follow `commitlint.config.js` (conventional commit types, single-line header up to 150 chars).
	- PR description should include a brief "## Summary" and a short "How to test" section with steps.
	- In PR checklist, confirm: lint passed, tests passed, static analysis passed, translations added (if UI text changed), module status updated (if modules changed).

End of copilot instructions.
