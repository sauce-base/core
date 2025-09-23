# E2E Testing with Playwright

## Quick Start

1. **Start development servers** (in one terminal):

    ```bash
    docker composer up -d
    ```

    Wait for Laravel to be ready (check logs with `docker composer logs -f app`), then run:

    ```bash
    npm run dev
    ```

2. **Run tests** (in another terminal):

    ```bash
    npm run test:e2e
    ```

    If you want to add or run tests for a specific file, use:

    ```bash
    npm run test:e2e modules/Auth/tests/e2e/login.spec.ts
    ```

    If you want to add or run tests for a specific module, use:

    ```bash
    npm run test:e2e modules/Auth
    ```
