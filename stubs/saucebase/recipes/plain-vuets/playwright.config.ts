/**
 * Default Playwright configuration for Auth module E2E tests.
 * 
 * @returns Array of Playwright project configurations
 */
export default [
    {
        name: '{Module}',
        testDir: 'modules/{Module}/tests/e2e',
    }
];

