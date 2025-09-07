import { computed, onMounted, ref } from 'vue';
import {
    getProviderUIConfig,
    type ProviderUIConfig,
} from '../lib/socialProviders';

export interface SocialProvider {
    name: string;
    ui: ProviderUIConfig;
}

export type SocialProviders = Record<string, SocialProvider>;

export function useSocialLogin() {
    const providers = ref<SocialProviders>({});
    const isLoading = ref(true);
    const error = ref<string | null>(null);
    const providersRoute = 'auth.social.providers';
    const canUseSocialLogin = computed(() => route().has(providersRoute));

    const fetchProviders = async () => {
        try {
            isLoading.value = true;
            error.value = null;

            const response = await fetch(route(providersRoute), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            const backendProviders = data.providers || {};

            // Merge backend data with frontend UI configuration
            const mergedProviders: SocialProviders = {};
            for (const [key, backendProvider] of Object.entries(
                backendProviders,
            )) {
                mergedProviders[key] = {
                    ...(backendProvider as any),
                    ui: getProviderUIConfig(key),
                };
            }

            providers.value = mergedProviders;
        } catch (err) {
            error.value =
                err instanceof Error
                    ? err.message
                    : 'Failed to fetch providers';
            console.error('Error fetching social providers:', err);
        } finally {
            isLoading.value = false;
        }
    };

    const loginWithProvider = (providerKey: string) => {
        if (!providers.value[providerKey]) {
            console.error(`Provider "${providerKey}" not found or not enabled`);
            return;
        }

        // Navigate to the social auth route
        window.location.href = `/auth/${providerKey}`;
    };

    const getEnabledProviders = () => {
        return Object.keys(providers.value);
    };

    const getProviderConfig = (providerKey: string): SocialProvider | null => {
        return providers.value[providerKey] || null;
    };

    onMounted(() => {
        if (canUseSocialLogin.value) {
            fetchProviders();
        }
    });

    return {
        providers,
        isLoading,
        error,
        loginWithProvider,
        getEnabledProviders,
        getProviderConfig,
        refetch: fetchProviders,
        canUseSocialLogin,
    };
}
