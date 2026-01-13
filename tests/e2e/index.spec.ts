import { expect, test } from '@playwright/test';
import { expectContentVisibleWithoutJS, expectSSREnabled } from './helpers/ssr';

test.describe('Landing page', () => {
    test('responds successfully when navigating to root', async ({ page }) => {
        const response = await page.goto('/');

        expect(response, 'Expected a navigation response').toBeTruthy();
        expect(
            response?.ok(),
            'Expected a successful status code',
        ).toBeTruthy();
    });

    test('uses SSR and contains rendered HTML', async ({ page }) => {
        await page.goto('/');

        // Verify SSR is enabled and renders the Index component
        await expectSSREnabled(page, 'Index');
    });

    test('content is visible without JavaScript (SSR proof)', async ({
        browser,
    }) => {
        // Create a context with JavaScript disabled
        const context = await browser.newContext({
            javaScriptEnabled: false,
        });
        const page = await context.newPage();

        await page.goto('/');

        // Verify content is visible without JS
        await expectContentVisibleWithoutJS(page);

        await context.close();
    });
});
