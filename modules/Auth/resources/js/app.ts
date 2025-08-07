// Auth Module TypeScript
console.log('Auth Module assets loaded successfully!');

// Define types for better TypeScript support
interface Window {
    testAuthModule?: () => string;
}

// Export a test function to global scope for browser testing
window.testAuthModule = function (): string {
    console.log('Auth Module is working!');
    return 'Auth module integration successful';
};

// Add visual indicator with proper typing
document.addEventListener('DOMContentLoaded', function (): void {
    console.log('Auth Module DOM ready');
});