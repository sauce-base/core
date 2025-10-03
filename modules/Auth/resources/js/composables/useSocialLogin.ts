import { type Component, computed, onMounted, ref } from 'vue';

import IconFacebook from '~icons/mdi/facebook';
import IconGithub from '~icons/mdi/github';
import IconGoogle from '~icons/mdi/google';

interface ProvidersResponse {
    providers?: Record<string, Omit<SocialProvider, 'ui'>>;
}

const PROVIDERS_ROUTE = 'auth.social.providers';

const mergeWithUiConfig = (
    backendProviders: Record<string, Omit<SocialProvider, 'ui'>>,
): SocialProviders => {
    return Object.keys(backendProviders).reduce<SocialProviders>((acc, key) => {
        const provider = backendProviders[key];

        acc[key] = {
            ...provider,
            ui: getProviderUIConfig(key),
        } as SocialProvider;

        return acc;
    }, {});
};

const loadProviders = async (): Promise<SocialProviders> => {
    const response = await fetch(route(PROVIDERS_ROUTE), {
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: ProvidersResponse = await response.json();
    const backendProviders = (data.providers ?? {}) as Record<
        string,
        Omit<SocialProvider, 'ui'>
    >;

    return mergeWithUiConfig(backendProviders);
};

export interface ProviderUIConfig {
    name: string;
    icon: Component;
    colors: {
        text: string;
        bg: string;
        focus: string;
    };
    brandColor?: string;
}

export const SOCIAL_PROVIDER_UI_MAP: Record<string, ProviderUIConfig> = {
    google: {
        name: 'Google',
        icon: IconGoogle,
        colors: {
            text: 'text-white dark:text-gray-900',
            bg: 'bg-gray-900 border border-gray-900 hover:bg-gray-800 dark:bg-white dark:border-gray-300 dark:hover:bg-gray-50',
            focus: 'focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-blue-500 dark:focus:ring-offset-gray-800',
        },
        brandColor: '#4285f4',
    },
    github: {
        name: 'GitHub',
        icon: IconGithub,
        colors: {
            text: 'text-white',
            bg: 'bg-gray-900 hover:bg-gray-800 border border-gray-900',
            focus: 'focus:ring-2 focus:ring-gray-500 focus:ring-offset-2',
        },
        brandColor: '#333333',
    },
    facebook: {
        name: 'Facebook',
        icon: IconFacebook,
        colors: {
            text: 'text-white',
            bg: 'bg-blue-600 hover:bg-blue-700 border border-blue-600',
            focus: 'focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
        },
        brandColor: '#1877f2',
    },
};

export function getProviderUIConfig(providerKey: string): ProviderUIConfig {
    return SOCIAL_PROVIDER_UI_MAP[providerKey] || SOCIAL_PROVIDER_UI_MAP.google;
}

export interface SocialProvider {
    name: string;
    ui: ProviderUIConfig;
    [key: string]: unknown;
}

export type SocialProviders = Record<string, SocialProvider>;



export function useSocialLogin() {
    const providers = ref<SocialProviders>({});
    const isLoading = ref(true);
    const error = ref<string | null>(null);
    const canUseSocialLogin = computed(() => route().has(PROVIDERS_ROUTE));

    const fetchProviders = async () => {
        try {
            isLoading.value = true;
            error.value = null;

            if (!canUseSocialLogin.value) {
                providers.value = {};
                return;
            }

            providers.value = await loadProviders();
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

        window.location.href = route('auth.social.redirect', {
            provider: providerKey,
        });
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
