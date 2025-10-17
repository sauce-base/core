import { expect, test } from '@playwright/test';
import { testUsers } from '../../fixtures/users';
import { RegisterPage } from '../../pages/RegisterPage';

test.describe.parallel('Register Error Handling', () => {
    let registerPage: RegisterPage;

    test.beforeEach(async ({ page }) => {
        registerPage = new RegisterPage(page);
        await registerPage.goto();
    });

    test('shows error for duplicate email', async ({ page }) => {
        const user = testUsers.valid;

        await registerPage.register(user.name, user.email, user.password);

        await expect(registerPage.page).toHaveURL(registerPage.signupEndpoint);

        const errorAlert = page.locator('[role="alert"]').first();
        await expect(errorAlert).toBeVisible();
    });

    test('handles network failure gracefully', async () => {
        const user = testUsers.valid;

        await registerPage.mockNetworkFailure();
        const failedRequestPromise = registerPage.waitForFailedLoginRequest();

        await registerPage.register(user.name, user.email, user.password);

        await expect(registerPage.page).toHaveURL(registerPage.signupEndpoint);

        const failedRequest = await failedRequestPromise;
        expect(failedRequest.url()).toContain(registerPage.signupEndpoint);
    });

    test('handles server 500 error gracefully', async () => {
        const user = testUsers.valid;

        await registerPage.mockServerResponse(500, {
            message: 'Internal server error',
        });
        const responsePromise = registerPage.waitForLoginResponse();

        await registerPage.register(user.name, user.email, user.password);

        await expect(registerPage.page).toHaveURL(registerPage.signupEndpoint);

        const response = await responsePromise;
        expect(response.status()).toBe(500);
    });

    test('handles request timeout', async () => {
        const user = testUsers.valid;

        await registerPage.page.route(registerPage.signupEndpoint, async (route) => {
            // Simulate a timeout by delaying beyond Playwright's default
            await new Promise((resolve) => setTimeout(resolve, 35000));
            await route.abort('timedout');
        });

        await registerPage.register(user.name, user.email, user.password);

        // After timeout, form should still be on register page
        await expect(registerPage.page).toHaveURL(registerPage.signupEndpoint);
    });
});
