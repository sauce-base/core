import { expect, test } from '@playwright/test';

test.describe('Dashboard index page', () => {
    test('responds successfully when navigating to dashboard', async ({ page }) => {
        const response = await page.goto('/dashboard');

        expect(response, 'Expected a navigation response').toBeTruthy();
        expect(
            response?.ok(),
            'Expected a successful status code',
        ).toBeTruthy();
    });
});
