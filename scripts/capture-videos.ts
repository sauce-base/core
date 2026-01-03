#!/usr/bin/env tsx

import {
    chromium,
    type Browser,
    type BrowserContext,
    type Page,
} from '@playwright/test';
import fs from 'fs';
import path from 'path';

// Video step types
interface VideoStep {
    type: 'navigate' | 'click' | 'fill' | 'wait' | 'switchTheme' | 'custom';
    description?: string;
    route?: string;
    selector?: string;
    value?: string;
    duration?: number;
    theme?: 'light' | 'dark';
    action?: (page: Page) => Promise<void>;
}

// Video journey configuration
interface VideoJourneyConfig {
    name: string;
    description: string;
    auth: boolean;
    startTheme: 'light' | 'dark';
    steps: VideoStep[];
}

// Test user credentials (from modules/Auth/tests/e2e/fixtures/users.ts)
//TODO: get these from env vars or a secure source
const TEST_USER = {
    email: 'chef@saucebase.dev',
    password: 'secretsauce',
};

// Define video journeys
const videoJourneys: VideoJourneyConfig[] = [
    {
        name: 'auth-flow',
        description: 'Complete authentication flow with theme switching',
        auth: false,
        startTheme: 'light',
        steps: [
            {
                type: 'navigate',
                route: '/',
                description: 'Show home page',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'navigate',
                route: '/auth/login',
                description: 'Navigate to login page',
            },
            {
                type: 'wait',
                duration: 1500,
            },
            {
                type: 'fill',
                selector: '[data-testid="email"]',
                value: TEST_USER.email,
                description: 'Fill email',
            },
            {
                type: 'fill',
                selector: '[data-testid="password"]',
                value: TEST_USER.password,
                description: 'Fill password',
            },
            {
                type: 'wait',
                duration: 1000,
            },
            {
                type: 'click',
                selector: '[data-testid="login-button"]',
                description: 'Click login button',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'switchTheme',
                theme: 'dark',
                description: 'Switch to dark theme',
            },
            {
                type: 'wait',
                duration: 2000,
            },
        ],
    },
    {
        name: 'settings-tour',
        description: 'Tour through settings pages with theme change',
        auth: true,
        startTheme: 'light',
        steps: [
            {
                type: 'navigate',
                route: '/dashboard',
                description: 'Show dashboard',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'navigate',
                route: '/settings',
                description: 'Navigate to settings',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'navigate',
                route: '/settings/profile',
                description: 'Navigate to profile settings',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'switchTheme',
                theme: 'dark',
                description: 'Switch to dark theme',
            },
            {
                type: 'wait',
                duration: 2000,
            },
            {
                type: 'navigate',
                route: '/settings',
                description: 'Back to settings',
            },
            {
                type: 'wait',
                duration: 2000,
            },
        ],
    },
    {
        name: 'complete-tour',
        description: 'Complete app tour from landing to dashboard',
        auth: false,
        startTheme: 'light',
        steps: [
            {
                type: 'navigate',
                route: '/',
                description: 'Show home page',
            },
            {
                type: 'wait',
                duration: 1000,
            },
            {
                type: 'navigate',
                route: '/auth/register',
                description: 'Show register page',
            },
            {
                type: 'wait',
                duration: 1000,
            },
            {
                type: 'switchTheme',
                theme: 'dark',
                description: 'Switch to dark theme',
            },
            {
                type: 'wait',
                duration: 1500,
            },
            {
                type: 'navigate',
                route: '/auth/login',
                description: 'Navigate to login page',
            },
            {
                type: 'wait',
                duration: 1500,
            },
            {
                type: 'fill',
                selector: '[data-testid="email"]',
                value: TEST_USER.email,
                description: 'Fill email',
            },
            {
                type: 'fill',
                selector: '[data-testid="password"]',
                value: TEST_USER.password,
                description: 'Fill password',
            },
            {
                type: 'wait',
                duration: 800,
            },
            {
                type: 'click',
                selector: '[data-testid="login-button"]',
                description: 'Click login button',
            },
            {
                type: 'wait',
                duration: 1000,
            },
            {
                type: 'navigate',
                route: '/settings',
                description: 'Show settings',
            },
            {
                type: 'wait',
                duration: 1000,
            },
        ],
    },
];

// Helper: Set theme via localStorage before page navigation
async function setTheme(page: Page, theme: 'light' | 'dark'): Promise<void> {
    await page.addInitScript((selectedTheme) => {
        localStorage.setItem('vueuse-color-scheme', selectedTheme);
    }, theme);
}

// Helper: Switch theme during recording (with visual transition)
async function switchTheme(page: Page, theme: 'light' | 'dark'): Promise<void> {
    await page.evaluate((selectedTheme) => {
        localStorage.setItem('vueuse-color-scheme', selectedTheme);
        // Trigger storage event to update theme
        window.dispatchEvent(
            new StorageEvent('storage', {
                key: 'vueuse-color-scheme',
                newValue: selectedTheme,
            }),
        );
    }, theme);
    // Wait for theme transition to complete
    await page.waitForTimeout(800);
}

// Helper: Authenticate user by performing login
async function authenticateUser(page: Page): Promise<void> {
    await page.goto('/auth/login');

    // Wait for login page to load
    await page.waitForSelector('[data-testid="email"]', { timeout: 10000 });

    // Fill login form
    await page.getByTestId('email').fill(TEST_USER.email);
    await page.getByTestId('password').fill(TEST_USER.password);

    // Submit form
    await page.getByTestId('login-button').click();

    // Wait for redirect to dashboard
    await page.waitForURL('/dashboard', { timeout: 10000 });
}

// Helper: Wait for page to be fully ready
async function waitForPageReady(page: Page): Promise<void> {
    // Wait for network to be idle
    await page.waitForLoadState('networkidle');

    // Wait for Inertia page data to be available (SSR hydration)
    await page.waitForFunction(
        () => {
            const pageEl = document.querySelector('[data-page]');
            if (!pageEl) return false;

            const pageData = pageEl.getAttribute('data-page');
            return pageData && pageData.length > 0;
        },
        { timeout: 10000 },
    );

    // Wait for all images to load
    await page.evaluate(() => {
        return Promise.all(
            Array.from(document.images)
                .filter((img) => !img.complete)
                .map(
                    (img) =>
                        new Promise((resolve) => {
                            img.onload = img.onerror = resolve;
                        }),
                ),
        );
    });

    // Small delay for animations to complete
    await page.waitForTimeout(500);
}

// Helper: Execute a single video step
async function executeStep(page: Page, step: VideoStep): Promise<void> {
    if (step.description) {
        console.log(`    → ${step.description}`);
    }

    switch (step.type) {
        case 'navigate':
            if (!step.route) {
                throw new Error('Navigate step requires route');
            }
            await page.goto(step.route);
            await waitForPageReady(page);
            await page.waitForTimeout(1500);
            break;

        case 'click':
            if (!step.selector) {
                throw new Error('Click step requires selector');
            }
            await page.click(step.selector);
            await page.waitForTimeout(1000);
            break;

        case 'fill':
            if (!step.selector || !step.value) {
                throw new Error('Fill step requires selector and value');
            }
            await page.fill(step.selector, step.value);
            await page.waitForTimeout(800);
            break;

        case 'switchTheme':
            if (!step.theme) {
                throw new Error('SwitchTheme step requires theme');
            }
            await switchTheme(page, step.theme);
            await page.waitForTimeout(1500);
            break;

        case 'wait':
            if (!step.duration) {
                throw new Error('Wait step requires duration');
            }
            await page.waitForTimeout(step.duration);
            break;

        case 'custom':
            if (!step.action) {
                throw new Error('Custom step requires action function');
            }
            await step.action(page);
            break;

        default:
            throw new Error(`Unknown step type: ${(step as VideoStep).type}`);
    }
}

// Main: Record a video journey
async function recordVideoJourney(
    browser: Browser,
    config: VideoJourneyConfig,
    baseURL: string,
    outputDir: string,
): Promise<string> {
    // Ensure output directory exists
    if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
    }

    // Create browser context with video recording
    const context: BrowserContext = await browser.newContext({
        baseURL,
        viewport: { width: 1920, height: 1080 },
        ignoreHTTPSErrors: true,
        recordVideo: {
            dir: outputDir,
            size: { width: 1920, height: 1080 },
        },
    });

    const page: Page = await context.newPage();

    try {
        // Set initial theme
        await setTheme(page, config.startTheme);

        // Authenticate if required
        if (config.auth) {
            console.log('    → Authenticating...');
            await authenticateUser(page);
        }

        // Execute each step in the journey
        for (const step of config.steps) {
            await executeStep(page, step);
        }

        // Wait a bit before closing to ensure last frame is captured
        await page.waitForTimeout(1000);
    } finally {
        // Close context to finish video recording
        await context.close();
    }

    // Get the video path (Playwright saves with a UUID name)
    const videoPath = await page.video()?.path();
    if (!videoPath) {
        throw new Error('Video path not found');
    }

    // Rename video to journey name
    const targetPath = path.join(outputDir, `${config.name}.webm`);
    if (fs.existsSync(targetPath)) {
        fs.unlinkSync(targetPath);
    }
    fs.renameSync(videoPath, targetPath);

    // Get video file size
    const stats = fs.statSync(targetPath);
    const fileSizeMB = (stats.size / (1024 * 1024)).toFixed(2);

    return `${config.name}.webm (${fileSizeMB} MB)`;
}

// Main function
async function main(): Promise<void> {
    // Parse CLI arguments
    const args = process.argv.slice(2);
    const onlyFlag = args.find((arg) => arg.startsWith('--only='));
    const headedFlag = args.includes('--headed');
    const filter = onlyFlag ? onlyFlag.split('=')[1] : null;

    // Load environment variables
    const baseURL = process.env.APP_URL || 'https://localhost';
    const outputDir = path.join(process.cwd(), 'public/videos');

    // Filter journeys if --only flag provided
    const journeysToRecord = filter
        ? videoJourneys.filter(
              (j) => j.name.includes(filter) || j.description.includes(filter),
          )
        : videoJourneys;

    if (journeysToRecord.length === 0) {
        console.error(`❌ No video journeys match filter: ${filter}`);
        process.exit(1);
    }

    // Display configuration
    console.log('🎥 Starting video recording...');
    console.log(`📍 Base URL: ${baseURL}`);
    console.log(`💾 Output: ${outputDir}`);
    if (filter) {
        console.log(`🔍 Filter: ${filter}`);
    }
    console.log(`📹 Recording ${journeysToRecord.length} video(s)...\n`);

    // Launch browser
    const browser = await chromium.launch({
        headless: !headedFlag,
    });

    let successCount = 0;
    const failures: Array<{ config: VideoJourneyConfig; error: unknown }> = [];

    try {
        // Record each video journey
        for (const [index, config] of journeysToRecord.entries()) {
            const progress = `[${index + 1}/${journeysToRecord.length}]`;
            console.log(`${progress} ${config.name}`);
            console.log(`  ${config.description}`);

            try {
                const result = await recordVideoJourney(
                    browser,
                    config,
                    baseURL,
                    outputDir,
                );
                console.log(`  ✓ ${result}\n`);
                successCount++;
            } catch (error) {
                console.error(
                    `  ✗ Failed:`,
                    error instanceof Error ? error.message : error,
                );
                failures.push({ config, error });
            }
        }
    } finally {
        await browser.close();
    }

    // Display summary
    console.log('='.repeat(50));
    console.log(`✅ Success: ${successCount}/${journeysToRecord.length}`);

    if (failures.length > 0) {
        console.log(`❌ Failed: ${failures.length}/${journeysToRecord.length}`);
        failures.forEach(({ config, error }) => {
            console.log(
                `  - ${config.name}: ${error instanceof Error ? error.message : error}`,
            );
        });
        process.exit(1);
    }

    console.log('\n✅ All videos recorded successfully!');
    console.log(`📁 Videos saved to: ${outputDir}`);
}

// Run the script
main().catch((error) => {
    console.error('\n❌ Fatal error:', error);
    process.exit(1);
});
