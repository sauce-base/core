import { expect, test } from '@playwright/test';

test.describe('Test Reporting', () => {
    test('should pass - basic assertion', async ({ page }) => {
        await page.goto('/');
        expect(true).toBe(true);
    });

    test('should fail - intentional failure for testing CI reporting', async ({
        page,
    }) => {
        await page.goto('/');
        // This will fail to test the reporting
        expect(1 + 1).toBe(3);
    });
});
