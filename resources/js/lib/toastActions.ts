// Registry for custom toast action functions
export const toastActionRegistry: Record<string, () => void> = {
    // Built-in actions
    reloadPage: () => window.location.reload(),
    goBack: () => window.history.back(),
    // Add more custom actions as needed
};

// Helper to register new toast actions dynamically
export function registerToastAction(name: string, handler: () => void) {
    toastActionRegistry[name] = handler;
}
