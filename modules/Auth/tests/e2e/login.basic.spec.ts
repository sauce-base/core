import { expect, test } from '@playwright/test';
import { testUsers, validationTestCases } from './fixtures/users';
import { LoginPage } from './pages/LoginPage';

test.describe.parallel('Login Basics', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await loginPage.goto();
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

    const validationTests = [
        { name: 'empty email', testCase: validationTestCases.emptyEmail },
        {
            name: 'invalid email format',
            testCase: validationTestCases.invalidEmail,
        },
        { name: 'empty password', testCase: validationTestCases.emptyPassword },
    ];

    for (const { name, testCase } of validationTests) {
        test(`shows validation error for ${name}`, async () => {
            await loginPage.login(testCase.email, testCase.password);
            await expect(
                loginPage.page.getByTestId(testCase.errorTestId),
            ).toBeVisible();
        });
    }

    test('validates form before submission', async () => {
        await loginPage.loginButton.click();

        await expect(loginPage.page).toHaveURL(loginPage.loginEndpoint);

        await expect(loginPage.page.getByTestId('email-error')).toBeVisible();
    });

});
