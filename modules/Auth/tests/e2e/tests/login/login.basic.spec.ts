import { expect, test } from '@playwright/test';
import { testUsers } from '../../fixtures/users';
import { LoginPage } from '../../pages/LoginPage';

test.describe.parallel('Login Basics', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
        await loginPage.expectToBeVisible();
    });

    async function expectSuccessfulLogin() {
        await expect(loginPage.page).toHaveURL('/dashboard');
    }

    test('logs in with valid credentials and redirects to dashboard', async () => {
        const user = testUsers.valid;
        await loginPage.login(user.email, user.password);
        await expectSuccessfulLogin();
    });

    test('logs in with remember me option', async () => {
        const user = testUsers.valid;
        await loginPage.login(user.email, user.password, true);

        await expect(loginPage.rememberCheckbox).toBeChecked();
        await expectSuccessfulLogin();
    });

    test('redirects authenticated users away from login page', async ({
        page,
    }) => {
        const user = testUsers.valid;
        await loginPage.login(user.email, user.password);
        await expectSuccessfulLogin();

        await page.goto('/auth/login');

        await expect(page).toHaveURL('/dashboard');
    });

    test('toggles password visibility', async () => {
        const user = testUsers.valid;
        await loginPage.passwordInput.fill(user.password);

        await loginPage.expectPasswordHidden();

        await loginPage.togglePasswordVisibility();
        await loginPage.expectPasswordVisible();

        await loginPage.togglePasswordVisibility();
        await loginPage.expectPasswordHidden();
    });

    test('submits form on Enter key press', async () => {
        const user = testUsers.valid;
        await loginPage.emailInput.fill(user.email);
        await loginPage.passwordInput.fill(user.password);

        await loginPage.passwordInput.press('Enter');

        await expectSuccessfulLogin();
    });
});
