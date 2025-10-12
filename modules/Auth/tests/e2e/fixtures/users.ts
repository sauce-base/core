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
    withSpaces: {
        name: 'User With Spaces',
        email: '  test@example.com  ',
        password: 'secretsauce',
        normalized: 'test@example.com',
    },
    specialPassword: {
        name: 'Special Chars User',
        email: 'special@example.com',
        password: 'P@ssw0rd!#$%^&*()',
    },
} as const;