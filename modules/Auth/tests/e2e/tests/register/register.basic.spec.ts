import { expect, test } from '@playwright/test';
import { RegisterPage } from '../../pages/RegisterPage';
import { faker } from '@faker-js/faker';

test.describe.parallel('Register Basics', () => {
    let registerPage: RegisterPage;

    test.beforeEach(async ({ page }) => {
        registerPage = new RegisterPage(page);
        await registerPage.goto();
        await registerPage.expectToBeVisible();
    });

    test('registers with valid details and redirects to dashboard', async () => {
        const user = {
            name: faker.person.fullName(),
            email: faker.internet.exampleEmail(),
            password: faker.internet.password(),
        };

        await registerPage.register(user.name, user.email, user.password);

        await expect(registerPage.page).toHaveURL('/dashboard');
    });
});
