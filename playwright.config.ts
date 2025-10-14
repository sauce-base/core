import { defineConfig, devices } from '@playwright/test';
import 'dotenv/config';
import { collectModulePlaywrightConfigs } from './module-loader.js';

const BASE_URL = process.env.APP_URL || 'http://localhost';

type ModulePlaywrightConfig = {
    name: string;
    testDir: string;
    use?: Record<string, unknown>;
    // allow arbitrary extra properties
    [key: string]: unknown;
};

async function createConfig() {

    // Collect Playwright configs from all modules
    // cast to ModulePlaywrightConfig[] so modules can include extra keys beyond name/testDir/use
    const modules = (await collectModulePlaywrightConfigs() || []) as ModulePlaywrightConfig[];

    const testDevices = [
        'Desktop Chrome',
        // 'Desktop Firefox',
        // 'Desktop Safari',
        // 'iPhone 14', 
        // Add more devices here if needed
    ];

    const projects = [
        /**
         * Default project for core E2E tests
         */
        {
            name: '@Core',
            testDir: './tests/e2e',
            use: {}, // will be extended below
        } as ModulePlaywrightConfig,
        // Add more projects here if needed
    ].concat(modules as ModulePlaywrightConfig[])
        .map(project => {
            // Extend each project with the selected devices
            return testDevices.map(device => {
                return {
                    ...project,
                    name: `${project.name} [${device}]`,
                    use: { ...devices[device], ...(project.use ?? {}), },
                };
            });
        })
        .flat();

    /**
     * @see https://playwright.dev/docs/test-configuration
     */
    return defineConfig({
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
        /* Folder for test artifacts such as screenshots, videos, traces, etc. */
        testDir: './tests/e2e',
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
        projects: projects,
        webServer: {
            command: `npx vite --port 5173`,
            timeout: 10 * 1000,
            reuseExistingServer: !process.env.CI,
        },
    });
};

export default createConfig();