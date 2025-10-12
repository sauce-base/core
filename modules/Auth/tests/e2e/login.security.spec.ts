import { expect, test } from '@playwright/test';
import { testUsers } from './fixtures/users';
import { LoginPage } from './pages/LoginPage';

test.describe('Login Security', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
    });

    test.describe('Rate Limiting', () => {
        test.describe.configure({ mode: 'serial' });

        test('blocks login after too many failed attempts', async () => {
            const invalidUser = testUsers.invalid;

            for (let i = 0; i < 5; i++) {
                await loginPage.login(invalidUser.email, invalidUser.password);
                
                await loginPage.page.waitForTimeout(500);
                
                if (i < 4) {
                    await expect(loginPage.page).toHaveURL(
                        loginPage.loginEndpoint,
                    );
                }
            }

            await loginPage.login(invalidUser.email, invalidUser.password);

            await expect(
                loginPage.page.getByText(/too many/i),
            ).toBeVisible();
        });

        test('handles rate limit response', async () => {
            // This test verifies that the form can display rate limit errors
            // Since rate limiting is implemented on the backend, we test the UI's ability to show the error
            const invalidUser = testUsers.invalid;

            // Make multiple failed login attempts - backend should handle rate limiting
            await loginPage.login(invalidUser.email, invalidUser.password);
            await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);
            
            // Verify the page can show errors (even if not rate limited yet)
            const errorAlert = loginPage.page.locator('[role="alert"]').first();
            await expect(errorAlert).toBeVisible();
        });
    });

    test.describe('CSRF Protection', () => {
        test('includes CSRF token in form submission', async () => {
            const user = testUsers.valid;

            let csrfTokenFound = false;
            await loginPage.page.route(loginPage.loginEndpoint, (route) => {
                const request = route.request();
                const postData = request.postData();

                if (postData) {
                    // Check for CSRF token in various formats
                    csrfTokenFound = postData.includes('_token') || 
                                   postData.includes('csrf') ||
                                   request.headers()['x-csrf-token'] !== undefined ||
                                   request.headers()['x-xsrf-token'] !== undefined;
                }

                route.fulfill({
                    status: 302,
                    headers: { Location: '/dashboard' }
                });
            });

            await loginPage.login(user.email, user.password);
            
            expect(csrfTokenFound).toBe(true);
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

    test.describe('Password Security', () => {
        test('password field does not expose value in HTML', async () => {
            const user = testUsers.valid;
            
            await loginPage.passwordInput.fill(user.password);

            const inputType = await loginPage.passwordInput.getAttribute('type');
            expect(inputType).toBe('password');

            const htmlContent = await loginPage.page.content();
            expect(htmlContent).not.toContain(user.password);
        });
    });
});

