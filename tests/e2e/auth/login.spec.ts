import { expect, test } from '@playwright/test';
import { testUsers, validationTestCases } from '../fixtures/users';
import { DashboardPage } from '../pages/DashboardPage';
import { LoginPage } from '../pages/LoginPage';

test.describe('Login Flow', () => {
    let loginPage: LoginPage;
    let dashboardPage: DashboardPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        dashboardPage = new DashboardPage(page);
        await loginPage.goto();
    });

    test('should display login form correctly', async () => {
        await loginPage.expectToBeVisible();
        await expect(loginPage.emailInput).toBeVisible();
        await expect(loginPage.passwordInput).toBeVisible();
        await expect(loginPage.loginButton).toBeVisible();
        await expect(loginPage.rememberCheckbox).toBeVisible();
    });

    test('should successfully login with valid credentials and redirect to dashboard', async () => {
        const user = testUsers.valid;

        await loginPage.login(user.email, user.password);

        // Verify redirect to dashboard
        await dashboardPage.expectToBeVisible();
        await expect(loginPage.page).toHaveURL('/dashboard');
    });

    test('should login with remember me option', async () => {
        const user = testUsers.valid;

        await loginPage.login(user.email, user.password, true);

        // Verify remember checkbox was checked
        await expect(loginPage.rememberCheckbox).toBeChecked();

        // Verify redirect to dashboard
        await dashboardPage.expectToBeVisible();
    });

    test('should show validation error for empty email', async () => {
        const testCase = validationTestCases.emptyEmail;

        await loginPage.login(testCase.email, testCase.password);

        await loginPage.expectValidationError(testCase.expectedError);
    });

    test('should show validation error for invalid email format', async () => {
        const testCase = validationTestCases.invalidEmail;

        await loginPage.login(testCase.email, testCase.password);

        await loginPage.expectValidationError(testCase.expectedError);
    });

    test('should show validation error for empty password', async () => {
        const testCase = validationTestCases.emptyPassword;

        await loginPage.login(testCase.email, testCase.password);

        await loginPage.expectValidationError(testCase.expectedError);
    });

    test('should toggle password visibility', async () => {
        await loginPage.passwordInput.fill('testpassword');

        // Initially password should be hidden
        await loginPage.expectPasswordHidden();

        // Click toggle to show password
        await loginPage.togglePasswordVisibility();
        await loginPage.expectPasswordVisible();

        // Click toggle again to hide password
        await loginPage.togglePasswordVisibility();
        await loginPage.expectPasswordHidden();
    });

    test('should navigate to sign up page', async () => {
        await loginPage.signUpLink.click();
        await expect(loginPage.page).toHaveURL('/register');
    });

    test('should navigate to forgot password page', async ({ page }) => {
        // Only test this if forgot password link is visible
        if (await loginPage.forgotPasswordLink.isVisible()) {
            await loginPage.forgotPasswordLink.click();
            await expect(page).toHaveURL('/forgot-password');
        }
    });

    test('should handle invalid credentials gracefully', async () => {
        const invalidUser = testUsers.invalid;

        await loginPage.login(invalidUser.email, invalidUser.password);

        // Should stay on login page and show error (this will depend on your backend implementation)
        await expect(loginPage.page).toHaveURL('/login');
    });

    test('should validate form before submission', async () => {
        // Try to submit empty form
        await loginPage.loginButton.click();

        // Should show validation errors without navigating
        await expect(loginPage.page).toHaveURL('/login');
        await loginPage.expectValidationError(
            'Please enter your email address',
        );
    });
});
