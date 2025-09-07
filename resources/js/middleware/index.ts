import { setupAuthMiddleware } from '@modules/Auth/resources/js/middleware/auth';

/**
 * Initialize all application middleware
 */
export function setupMiddleware() {
    setupAuthMiddleware();
}
