import { defineConfig, devices } from '@playwright/test';
import 'dotenv/config';

const BASE_URL = process.env.APP_URL || 'http://localhost';

/**
 * @see https://playwright.dev/docs/test-configuration
 */
export default defineConfig({
    testDir: './tests/e2e',
    /* Run tests in files in parallel */
    fullyParallel: true,
    /* Fail the build on CI if you accidentally left test.only in the source code. */
    forbidOnly: !!process.env.CI,
    /* Retry on CI only */
    retries: process.env.CI ? 2 : 0,
    /* Opt out of parallel tests on CI. */
    workers: process.env.CI ? 1 : undefined,
    /* Reporter to use. See https://playwright.dev/docs/test-reporters */
    reporter: [['html', { outputFolder: 'playwright-report' }], ['list']],
    /* Shared settings for all the projects below. See https://playwright.dev/docs/api/class-testoptions. */
    use: {
        /* Base URL to use in actions like `await page.goto('/')`. */
        baseURL: BASE_URL,

        /* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
        trace: 'on-first-retry',

        /* Take screenshot only on failures */
        screenshot: 'only-on-failure',

        /* Record video only on failures */
        video: 'retain-on-failure',

        /* Ignore HTTPS errors */
        ignoreHTTPSErrors: true,
    },

    /* Configure projects for major browsers */
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],

    webServer: {
        command: `npx vite --port 5173`,
        timeout: 10 * 1000,
        reuseExistingServer: !process.env.CI,
    },
});
