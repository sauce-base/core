import type { PageProps } from '@/types';
import { router } from '@inertiajs/vue3';
import { useAuthStore } from '../stores';

/**
 * Synchronize auth state between Inertia page props and Pinia store
 */
function syncAuthState(pageProps: PageProps | Record<string, unknown>) {
    if (!pageProps) return;

    const authStore = useAuthStore();

    // Type-safe check for auth.user
    if (
        'auth' in pageProps &&
        pageProps.auth &&
        typeof pageProps.auth === 'object' &&
        'user' in pageProps.auth
    ) {
        authStore.setUser(pageProps.auth.user as PageProps['auth']['user']);
    } else if (authStore.user) {
        authStore.clearUser();
    }
}

/**
 * Authentication middleware to handle auth state synchronization
 * between Inertia page props and Pinia store
 */
export function setupAuthMiddleware() {
    // Handle all page navigation events
    router.on('navigate', (event) => {
        syncAuthState(event.detail.page.props);
    });
}
