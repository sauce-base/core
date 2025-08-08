import { setupAuthMiddleware } from './auth';

/**
 * Initialize all application middleware
 */
export function setupMiddleware() {
    setupAuthMiddleware();
}
