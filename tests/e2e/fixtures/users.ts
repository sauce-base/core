export const testUsers = {
    valid: {
        name: 'Test User',
        email: 'test@example.com',
        password: 'secretsauce',
    },
    invalid: {
        email: 'invalid@example.com',
        password: 'wrongpassword',
    },
    admin: {
        name: 'Chef Saucier',
        email: 'admin@saucebase.dev',
        password: 'secretsauce',
    },
    user: {
        name: 'Regular User',
        email: 'user@example.com',
        password: 'secretsauce',
    },
} as const;

// Translation-agnostic validation test cases
// Tests check for error presence via data-testid, not specific text
export const validationTestCases = {
    emptyEmail: {
        email: '',
        password: 'secretsauce',
        errorTestId: 'email-error',
    },
    invalidEmail: {
        email: 'invalid-email',
        password: 'secretsauce',
        errorTestId: 'email-error',
    },
    emptyPassword: {
        email: 'test@example.com',
        password: '',
        errorTestId: 'password-error',
    },
} as const;
