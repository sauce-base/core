import { type Page } from '@playwright/test';
import { testUsers } from '../fixtures/users';
import { LoginPage } from '../pages/LoginPage';

export async function loginAsUser(page: Page) {
    const loginPage = new LoginPage(page);
    const user = testUsers.valid;

    await loginPage.goto();
    await loginPage.login(user.email, user.password);

    // Wait for redirect to dashboard
    await page.waitForURL('/dashboard');
}

export async function logout(page: Page) {
    // Assuming there's a logout button in the navigation
    await page.getByRole('button', { name: 'Log Out' }).click();
    await page.waitForURL('/');
}
