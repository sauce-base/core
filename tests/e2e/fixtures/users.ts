export const testUsers = {
    valid: {
        name: 'Test User',
        email: 'test@example.com',
        password: 'password',
    },
    invalid: {
        email: 'invalid@example.com',
        password: 'wrongpassword',
    },
} as const;

export const validationTestCases = {
    emptyEmail: {
        email: '',
        password: 'password123',
        expectedError: 'Please enter your email address',
    },
    invalidEmail: {
        email: 'invalid-email',
        password: 'password123',
        expectedError:
            'Please enter a valid email address (like john@example.com)',
    },
    emptyPassword: {
        email: 'test@example.com',
        password: '',
        expectedError: 'Please enter your password',
    },
    shortPassword: {
        email: 'test@example.com',
        password: '123',
        expectedError: 'Password must be at least 8 characters long',
    },
} as const;
