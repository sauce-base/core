import { expect, test } from '@playwright/test';
import { testUsers } from '../../fixtures/users';
import { LoginPage } from '../../pages/LoginPage';

test.describe.parallel('Logout Basics', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
        await loginPage.expectToBeVisible();
    });

    test('logs out from user menu and redirects to login', async ({ page }) => {
        const user = testUsers.valid;

        // Login first
        await loginPage.login(user.email, user.password);
        await expect(page).toHaveURL('/dashboard');

        // Open user menu by clicking the SidebarMenuTrigger button (contains user name)
        // Use the user's name text to reliably find the trigger button
        await page.getByRole('button', { name: user.name }).click();

        // Click the 'Log out' menu item
        await page.getByRole('menuitem', { name: /log out/i }).click();

        // After logout we expect to be redirected to the home page
        await expect(page).toHaveURL('/');

        // Visiting protected route should redirect back to login
        await page.goto('/dashboard');
        await expect(page).toHaveURL('/auth/login');
    });
});
