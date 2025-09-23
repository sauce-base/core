import {
    expect,
    type Locator,
    type Page,
    type Request,
    type Response,
} from '@playwright/test';

export class LoginPage {
    readonly page: Page;
    readonly emailInput: Locator;
    readonly passwordInput: Locator;
    readonly passwordToggle: Locator;
    readonly rememberCheckbox: Locator;
    readonly loginButton: Locator;
    readonly forgotPasswordLink: Locator;
    readonly signUpLink: Locator;
    readonly loginEndpoint: string;

    constructor(page: Page) {
        this.page = page;
        this.loginEndpoint = '/auth/login';
        this.emailInput = page.getByTestId('email');
        this.passwordInput = page.getByTestId('password');
        this.passwordToggle = page.getByTestId('password-toggle');
        this.rememberCheckbox = page.getByTestId('remember-me');
        this.loginButton = page.getByTestId('login-button');
        this.forgotPasswordLink = page.getByTestId('forgot-password-link');
        this.signUpLink = page.getByTestId('sign-up-link');
    }

    async goto() {
        await this.page.goto(this.loginEndpoint);
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
        await expect(this.page.getByTestId('login-form')).toBeVisible();
        await expect(this.emailInput).toBeVisible();
        await expect(this.passwordInput).toBeVisible();
        await expect(this.loginButton).toBeVisible();
    }

    async expectEmailError() {
        const emailError = this.page.getByTestId('email-error');
        await expect(emailError).toBeVisible();
    }

    async expectPasswordError() {
        const passwordError = this.page.getByTestId('password-error');
        await expect(passwordError).toBeVisible();
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

    /**
     * Generic method to check for any validation error by test ID
     */
    async expectValidationError(testId: string) {
        const errorElement = this.page.getByTestId(testId);
        await expect(errorElement).toBeVisible();
    }

    async waitForLoginResponse() {
        return this.page.waitForResponse((response: Response) =>
            response.url().includes(this.loginEndpoint),
        );
    }

    async waitForFailedLoginRequest() {
        return this.page.waitForEvent('requestfailed', (request: Request) =>
            request.url().includes(this.loginEndpoint),
        );
    }

    async mockNetworkFailure() {
        await this.page.route(this.loginEndpoint, (route) => route.abort());
    }

    async mockServerResponse(
        status: number,
        body: Record<string, unknown> = {},
    ) {
        await this.page.route(this.loginEndpoint, (route) => {
            route.fulfill({
                status,
                contentType: 'application/json',
                body: JSON.stringify(body),
            });
        });
    }

    async mockDelayedResponse(delayMs: number) {
        await this.page.route(this.loginEndpoint, async (route) => {
            await new Promise((resolve) => setTimeout(resolve, delayMs));
            await route.continue();
        });
    }
}
