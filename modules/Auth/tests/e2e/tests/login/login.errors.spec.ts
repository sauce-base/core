import { expect, test } from '@playwright/test';
import { testUsers } from '../../fixtures/users';
import { LoginPage } from '../../pages/LoginPage';

test.describe.parallel('Login Error Handling', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
    });

    test('shows error for invalid credentials', async () => {
        const user = testUsers.invalid;

        await loginPage.login(user.email, user.password);

        await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

        await loginPage.expectAlertToBeVisible();
    });

    test('handles network failure gracefully', async () => {
        const user = testUsers.valid;

        await loginPage.mockNetworkFailure();
        const failedRequestPromise = loginPage.waitForFailedLoginRequest();

        await loginPage.login(user.email, user.password);

        await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

        const failedRequest = await failedRequestPromise;
        expect(failedRequest.url()).toContain(loginPage.loginEndpoint);
    });

    test('handles server 500 error gracefully', async () => {
        const user = testUsers.valid;

        await loginPage.mockServerResponse(500, {
            message: 'Internal server error',
        });
        const responsePromise = loginPage.waitForLoginResponse();

        await loginPage.login(user.email, user.password);

        await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

        const response = await responsePromise;
        expect(response.status()).toBe(500);
    });

    test('handles request timeout', async () => {
        const user = testUsers.valid;

        await loginPage.page.route(loginPage.loginEndpoint, async (route) => {
            // Simulate a timeout by delaying beyond Playwright's default
            await new Promise((resolve) => setTimeout(resolve, 35000));
            await route.abort('timedout');
        });

        await loginPage.login(user.email, user.password);

        // After timeout, form should still be on login page
        await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);
    });
});
