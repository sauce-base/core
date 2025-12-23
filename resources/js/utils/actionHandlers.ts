import { useColorMode } from '@vueuse/core';

/**
 * Action handler function type.
 */
export type ActionHandler = (event: MouseEvent) => void | Promise<void>;

/**
 * Registry of action handlers.
 *
 * Maps action IDs to frontend handler functions.
 * Modules can register their own handlers using registerActionHandler().
 */
const actionHandlers: Record<string, ActionHandler> = {
    /**
     * Theme toggle action.
     */
    'ui.theme.toggle': (event: MouseEvent) => {
        event.preventDefault();

        const colorMode = useColorMode();
        colorMode.value = colorMode.value === 'dark' ? 'light' : 'dark';
    },

    /**
     * Language selector action.
     * TODO: Implement language selection logic
     */
    'ui.language.select': (event: MouseEvent) => {
        event.preventDefault();
        console.warn('Language selection not yet implemented');
    },
};

/**
 * Handle a menu action.
 *
 * @param actionId - The action ID (from MenuItem.meta.action)
 * @param event - The mouse click event
 */
export function handleAction(actionId: string, event: MouseEvent): void {
    const handler = actionHandlers[actionId];

    if (handler) {
        handler(event);
    } else {
        console.warn(`No handler registered for action: ${actionId}`);
    }
}

/**
 * Register a custom action handler.
 *
 * Allows modules to register additional action handlers at runtime.
 *
 * @param actionId - The action ID
 * @param handler - The handler function
 */
export function registerActionHandler(
    actionId: string,
    handler: ActionHandler,
): void {
    if (actionHandlers[actionId]) {
        console.warn(`Action handler already registered for: ${actionId}`);
    }

    actionHandlers[actionId] = handler;
}
