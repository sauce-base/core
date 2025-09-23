import { expect, test } from '@playwright/test';
import { testUsers } from './fixtures/users';
import { LoginPage } from './pages/LoginPage';

test.describe.parallel('Login Security', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
    });

    test.describe('Rate Limiting', () => {
        test('handles too many failed login attempts', async () => {
            const invalidUser = testUsers.invalid;

            await loginPage.mockServerResponse(429, {
                message: 'Too many attempts. Account temporarily locked.',
                retry_after: 900,
            });
            const responsePromise = loginPage.waitForLoginResponse();

            await loginPage.login(invalidUser.email, invalidUser.password);

            await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

            const response = await responsePromise;
            expect(response.status()).toBe(429);
        });
    });

    test.describe('Session Handling', () => {
        test('handles expired session gracefully', async () => {
            const user = testUsers.valid;

            await loginPage.mockServerResponse(401, {
                message: 'Session expired. Please log in again.',
                code: 'SESSION_EXPIRED',
            });
            const responsePromise = loginPage.waitForLoginResponse();

            await loginPage.login(user.email, user.password);

            const response = await responsePromise;
            expect(response.status()).toBe(401);
        });
    });

    test.describe('CSRF Protection', () => {
        test('includes CSRF token in form submission', async () => {
            const user = testUsers.valid;

            await loginPage.page.route(loginPage.loginEndpoint, (route) => {
                const request = route.request();
                const postData = request.postData();

                if (!postData) {
                    throw new Error('Login request did not include payload');
                }

                expect(postData.includes('_token') || postData.includes('csrf'))
                    .toBe(true);
                route.continue();
            });

            await loginPage.login(user.email, user.password);
        });

        test('rejects submission with invalid CSRF token', async () => {
            const user = testUsers.valid;

            await loginPage.mockServerResponse(419, {
                message: 'CSRF token mismatch',
                errors: { _token: ['The CSRF token is invalid'] },
            });
            const responsePromise = loginPage.waitForLoginResponse();

            await loginPage.login(user.email, user.password);

            await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

            const response = await responsePromise;
            expect(response.status()).toBe(419);
        });
    });
});
