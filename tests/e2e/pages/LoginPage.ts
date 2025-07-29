import { expect, type Locator, type Page } from '@playwright/test';

export class LoginPage {
    readonly page: Page;
    readonly emailInput: Locator;
    readonly passwordInput: Locator;
    readonly passwordToggle: Locator;
    readonly rememberCheckbox: Locator;
    readonly loginButton: Locator;
    readonly forgotPasswordLink: Locator;
    readonly signUpLink: Locator;
    readonly errorMessage: Locator;
    readonly statusMessage: Locator;

    constructor(page: Page) {
        this.page = page;
        this.emailInput = page.getByTestId('email');
        this.passwordInput = page.getByTestId('password');
        this.passwordToggle = page.getByTestId('password-toggle');
        this.rememberCheckbox = page.getByLabel('Remember me');
        this.loginButton = page.getByRole('button', { name: 'Log in' });
        this.forgotPasswordLink = page.getByRole('link', {
            name: 'Forgot your password?',
        });
        this.signUpLink = page.getByRole('link', { name: 'Sign up' });
        this.errorMessage = page.locator('[data-testid="form-error"]');
        this.statusMessage = page.locator('.text-green-600');
    }

    async goto() {
        await this.page.goto('/login');
    }

    async login(email: string, password: string, remember = false) {
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);

        if (remember) {
            await this.rememberCheckbox.check();
        }

        await this.loginButton.click();
    }

    async expectToBeVisible() {
        await expect(this.page.getByText('Welcome back')).toBeVisible();
        await expect(this.emailInput).toBeVisible();
        await expect(this.passwordInput).toBeVisible();
        await expect(this.loginButton).toBeVisible();
    }

    async expectEmailError(message?: string) {
        const emailError = this.page.getByTestId('email-error');
        await expect(emailError).toBeVisible();
        if (message) {
            await expect(emailError).toHaveText(message);
        }
    }

    async expectPasswordError(message?: string) {
        const passwordError = this.page.getByTestId('password-error');
        await expect(passwordError).toBeVisible();
        if (message) {
            await expect(passwordError).toHaveText(message);
        }
    }

    async togglePasswordVisibility() {
        await this.passwordToggle.click();
    }

    async expectPasswordVisible() {
        await expect(this.passwordInput).toHaveAttribute('type', 'text');
    }

    async expectPasswordHidden() {
        await expect(this.passwordInput).toHaveAttribute('type', 'password');
    }

    async expectValidationError(message: string) {
        const errorElement = this.page.getByText(message);
        await expect(errorElement).toBeVisible();
    }
}
