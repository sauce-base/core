import { expect, type Locator, type Page } from '@playwright/test';

export class DashboardPage {
    readonly page: Page;
    readonly heading: Locator;
    readonly userMenu: Locator;
    readonly logoutButton: Locator;
    readonly profileLink: Locator;

    constructor(page: Page) {
        this.page = page;
        this.heading = page.locator('h2', { hasText: 'Dashboard' });
        this.userMenu = page.getByRole('button', { name: /user menu/i });
        this.logoutButton = page.getByRole('button', { name: 'Log Out' });
        this.profileLink = page.getByRole('link', { name: 'Profile' });
    }

    async expectToBeVisible() {
        // Wait for navigation to dashboard first
        await expect(this.page).toHaveURL('/dashboard');
        // Then check for any sign we're on the dashboard page
        await expect(this.page.getByText("You're logged in!")).toBeVisible();
    }

    async expectWelcomeMessage(userName?: string) {
        if (userName) {
            await expect(
                this.page.getByText(`Welcome, ${userName}!`),
            ).toBeVisible();
        } else {
            await expect(this.page.getByText(/welcome/i)).toBeVisible();
        }
    }

    async logout() {
        await this.userMenu.click();
        await this.logoutButton.click();
    }

    async goToProfile() {
        await this.userMenu.click();
        await this.profileLink.click();
    }

    async expectUserName(name: string) {
        await expect(this.page.getByText(name)).toBeVisible();
    }
}
