import { expect, test } from '@playwright/test';
import { testUsers } from '../../../Auth/tests/e2e/fixtures/users';
import { LoginPage } from '../../../Auth/tests/e2e/pages/LoginPage';

test.describe('Navigation Module', () => {
    test.beforeEach(async ({ page }) => {
        // Authenticate before accessing dashboard
        const loginPage = new LoginPage(page);
        await loginPage.goto();
        await loginPage.login(testUsers.valid.email, testUsers.valid.password);

        // Wait for redirect to dashboard
        await expect(page).toHaveURL('/dashboard');
    });

    test.describe('Main Navigation', () => {
        test('should display navigation items from server-side registry', async ({
            page,
        }) => {
            // Wait for sidebar to be visible
            await expect(
                page.locator('[data-sidebar="sidebar"]'),
            ).toBeVisible();

            // Check that Dashboard link exists in sidebar navigation
            const dashboardLink = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /dashboard/i });
            await expect(dashboardLink).toBeVisible();
        });

        test('should highlight active navigation item', async ({ page }) => {
            // Dashboard should be active when on /dashboard
            // Find the dashboard link and check its parent button has data-active
            const dashboardLink = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /dashboard/i });

            await expect(dashboardLink).toHaveAttribute('data-active', 'true');
        });

        test('should update active state when navigating', async ({ page }) => {
            // Dashboard should be active initially
            const dashboardLinkInitial = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /dashboard/i });
            await expect(dashboardLinkInitial).toHaveAttribute(
                'data-active',
                'true',
            );

            // Click on a different navigation link (if roles link exists, use it)
            const rolesLink = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /roles/i });

            // Check if roles link exists and is visible
            const rolesLinkCount = await rolesLink.count();

            if (rolesLinkCount > 0) {
                await rolesLink.click();
                await expect(page).toHaveURL(/\/roles/);

                // Roles link should now be active
                await expect(rolesLink).toHaveAttribute('data-active', 'true');

                // Dashboard link should no longer be active
                await expect(dashboardLinkInitial).toHaveAttribute(
                    'data-active',
                    'false',
                );
            }
        });

        test('should display icons for navigation items', async ({ page }) => {
            // Dashboard link should have an icon (svg element)
            const dashboardLink = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /dashboard/i });

            const icon = dashboardLink.locator('svg').first();
            await expect(icon).toBeVisible();
        });
    });

    test.describe('User Menu', () => {
        test('should display user menu with user info', async ({ page }) => {
            // Click user menu trigger
            const userMenuTrigger = page.getByTestId('user-menu-trigger');
            await expect(userMenuTrigger).toBeVisible();
            await userMenuTrigger.click();

            // Wait for dropdown menu to be visible
            const dropdownMenu = page.locator('[role="menu"]');
            await expect(dropdownMenu).toBeVisible();

            // Check that user email is displayed in the dropdown content (not the trigger)
            const dropdownEmail = page
                .locator('[role="menu"]')
                .getByText(testUsers.valid.email);
            await expect(dropdownEmail).toBeVisible();
        });

        test('should display user menu items', async ({ page }) => {
            // Open user menu
            const userMenuTrigger = page.getByTestId('user-menu-trigger');
            await userMenuTrigger.click();

            // Settings link should be in dropdown
            const settingsLink = page.getByRole('menuitem', {
                name: /settings/i,
            });
            await expect(settingsLink).toBeVisible();
        });

        test('should handle logout action', async ({ page }) => {
            // Open user menu
            const userMenuTrigger = page.getByTestId('user-menu-trigger');
            await userMenuTrigger.click();

            // Click logout
            const logoutButton = page.getByRole('menuitem', {
                name: /log out/i,
            });
            await expect(logoutButton).toBeVisible();

            // Setup dialog handler to confirm logout
            page.once('dialog', (dialog) => {
                expect(dialog.message()).toContain('log out');
                dialog.accept();
            });

            await logoutButton.click();

            // Should redirect to home page after logout
            await expect(page).toHaveURL('/');
        });
    });

    test.describe('Navigation Ordering', () => {
        test('should display items in correct order based on order property', async ({
            page,
        }) => {
            // Get navigation menu buttons from the content section (not header)
            const navButtons = page
                .locator('[data-sidebar="sidebar"] [data-sidebar="content"]')
                .locator('[data-sidebar="menu-button"]');

            // Dashboard should be first (order: 100)
            const firstButton = navButtons.first();
            await expect(firstButton).toContainText(/dashboard/i);
        });
    });

    test.describe('Settings Navigation', () => {
        test('should display settings navigation when on settings page', async ({
            page,
        }) => {
            // Navigate to settings via user menu
            const userMenuTrigger = page.getByTestId('user-menu-trigger');
            await userMenuTrigger.click();

            const settingsLink = page.getByRole('menuitem', {
                name: /settings/i,
            });
            await expect(settingsLink).toBeVisible();
            await settingsLink.click();

            // Wait for navigation
            await expect(page).toHaveURL(/\/settings/);

            // Settings page should have its own navigation sidebar
            // The General settings link should be visible
            const generalLink = page
                .locator('[data-sidebar="sidebar"]')
                .getByRole('link', { name: /general/i });
            await expect(generalLink).toBeVisible();
        });
    });
});
