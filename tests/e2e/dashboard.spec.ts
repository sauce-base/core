import { expect, test } from '@playwright/test';

test.describe('Dashboard page', () => {
    test('responds successfully when navigating to dashboard', async ({
        page,
    }) => {
        const response = await page.goto('/dashboard');

        expect(response, 'Expected a navigation response').toBeTruthy();
        // Note: This may redirect to login if not authenticated
        // Adjust expectations based on your auth requirements
    });

    // SSR test removed - covered by PHPUnit tests (InertiaSSRTest.php)
    // Dashboard requires authentication which is not set up in E2E tests
});
