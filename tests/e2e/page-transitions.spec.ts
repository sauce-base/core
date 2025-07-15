import { expect, test } from '@playwright/test';

test.describe('Page Transitions', () => {
    test.beforeEach(async ({ page }) => {
        // Go to the login page
        await page.goto('/login');
    });

    test('should display login page without errors', async ({ page }) => {
        // Check that the page loads
        await expect(page).toHaveTitle(/Log in/);
        await expect(page.getByRole('heading', { name: 'Welcome back' })).toBeVisible();
    });

    test('should have smooth transitions between auth pages', async ({
        page,
    }) => {
        // Test navigation from login to register
        await page.click('text=Sign up');

        // Wait for the page to load
        await expect(page).toHaveTitle(/Register/);
        await expect(
            page.getByRole('heading', { name: 'Create your account' }),
        ).toBeVisible();

        // Navigate back to login
        await page.click('text=Already registered?');
        await expect(page).toHaveTitle(/Log in/);

        // Go to forgot password
        await page.click('text=Forgot your password?');
        await expect(page).toHaveTitle(/Forgot Password/);
        await expect(
            page.getByText('Forgot your password? No problem'),
        ).toBeVisible();
    });

    test('should have page transition wrapper elements', async ({ page }) => {
        // Check that the page transition wrapper exists
        const transitionWrapper = page.locator('.page-transition-wrapper');
        await expect(transitionWrapper).toBeVisible();

        // Navigate to register and check wrapper exists there too
        await page.click('text=Sign up');
        await expect(transitionWrapper).toBeVisible();
    });

    test('should respect reduced motion preferences', async ({ page }) => {
        // Set reduced motion preference
        await page.emulateMedia({ reducedMotion: 'reduce' });

        // Navigate between pages
        await page.click('text=Sign up');
        await expect(page).toHaveTitle(/Register/);

        // The page should still work with reduced motion
        await page.click('text=Already registered?');
        await expect(page).toHaveTitle(/Log in/);
    });

    test('should handle page transitions with form submissions', async ({
        page,
    }) => {
        // Fill in the login form with invalid data to test error handling
        await page.fill(
            'input[placeholder="Enter your email"]',
            'invalid-email',
        );
        await page.fill(
            'input[placeholder="Enter your password"]',
            'wrong-password',
        );

        // Submit the form
        await page.click('button[type="submit"]');

        // Should stay on the login page and show errors
        await expect(page).toHaveTitle(/Log in/);

        // Check that the page transition wrapper is still present
        const transitionWrapper = page.locator('.page-transition-wrapper');
        await expect(transitionWrapper).toBeVisible();
    });
});

test.describe('Page Transitions - Authenticated', () => {
    test.beforeEach(async ({ page }) => {
        // For authenticated tests, you would need to login first
        // This is a placeholder for when authentication is set up
        await page.goto('/login');
    });

    test('should handle transitions between dashboard and profile', async ({
        page,
    }) => {
        // This test would require actual authentication
        // For now, just verify the login page loads
        await expect(page).toHaveTitle(/Log in/);

        // TODO: Add actual authenticated navigation tests
        // after setting up test authentication
    });
});
