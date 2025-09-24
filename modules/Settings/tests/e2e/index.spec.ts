import { expect, test } from '@playwright/test';

test.describe('Landing page', () => {
    test('responds successfully when navigating to root', async ({ page }) => {
        const response = await page.goto('/settings');

        expect(response, 'Expected a navigation response').toBeTruthy();
        expect(
            response?.ok(),
            'Expected a successful status code',
        ).toBeTruthy();
    });
});
