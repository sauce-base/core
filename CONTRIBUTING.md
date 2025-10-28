## Contributing to Saucebase

Thank you for helping improve this boilerplate. This is intentionally minimal — use it as a quick reference for contributors and automation.

1. Pick a PR template

- We provide multiple small templates under `.github/PULL_REQUEST_TEMPLATE/` (feature, bugfix, docs, chore). Use the one that best matches your work so GitHub presents the right scaffold.
- We intentionally removed the single default template to force the template chooser and avoid duplication.

2. Local setup (quick)

Clone and run the recommended bootstrap if you need full services:

```bash
./bin/setup-env
docker compose up -d
```

Or, for faster local iteration without Docker, run:

```bash
composer dev
npm run dev
```

3. Preflight checks (run before opening a PR)

- Format & lint
    - JS/TS: `npm run format` && `npm run lint`
    - PHP: `vendor/bin/pint` or `composer lint`
- Tests
    - Backend/unit: `composer test`
    - E2E: `npm run test` (Playwright)
- Static analysis & build
    - `composer analyse`
    - `npm run build` (if changing frontend/SSR)

4. Commit message rules

- Commits must follow `commitlint.config.js` (conventional commits).
    - Single-line header only (no body/footer).
    - Header max length: 150 chars.
    - Allowed types: `feat|fix|docs|style|refactor|perf|test|chore|ci|build|revert`.
    - Example: `feat: add OAuth callback`

5. Modules, i18n, and translations

- Modules live in `modules/<ModuleName>/`. Update `modules_statuses.json` when adding/removing modules.
- All user-facing strings must use the i18n system (`$t('key')`). Add Portuguese translations to `lang/pt_BR.json` when adding UI text.

6. Security

- Never commit secrets or `.env` files. Use environment/CI secrets instead.

7. PR Checklist (short)

- Include a brief `## Summary` and a short `How to test` section in your PR description.
- Confirm:
    - [ ] format & lint passed
    - [ ] backend & E2E tests passed (when applicable)
    - [ ] static analysis passed (phpstan) if relevant
    - [ ] translations added/updated (if UI text changed)
    - [ ] modules_statuses.json updated (if modules changed)

Questions or stuck?

- Open an issue with the `help wanted` label, or ask in the project channel you use for collaboration.

Thanks for contributing!
