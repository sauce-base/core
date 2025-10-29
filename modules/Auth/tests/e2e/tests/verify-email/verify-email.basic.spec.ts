import { test } from '@playwright/test';
import { RegisterPage } from '../../pages/RegisterPage';
import { VerifyEmailPage } from '../../pages/VerifyEmailPage';
import { faker } from '@faker-js/faker';

test.describe.parallel('Verify Email Basics', () => {
    let registerPage: RegisterPage;
    let verifyEmailPage: VerifyEmailPage;

    test.beforeEach(async ({ page }) => {
        registerPage = new RegisterPage(page);
        verifyEmailPage = new VerifyEmailPage(page);
    });

    function newUser() {
        return {
            name: faker.person.fullName(),
            email: faker.internet.exampleEmail(),
            password: faker.internet.password(),
        };
    }

    test('prevents unverified user from accessing dashboard after registration', async () => {
        const user = newUser();

        await registerPage.goto();
        await registerPage.expectToBeVisible();
        await registerPage.register(user.name, user.email, user.password);

        await verifyEmailPage.expectRedirectTo('/auth/verify-email');
        await verifyEmailPage.expectToBeVisible();

        await verifyEmailPage.page.goto('/dashboard');
        
        await verifyEmailPage.expectRedirectTo('/auth/verify-email');
    });
});
