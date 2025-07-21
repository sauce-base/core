import IconFacebook from '~icons/mdi/facebook';
import IconGithub from '~icons/mdi/github';
import IconGoogle from '~icons/mdi/google';

export interface ProviderUIConfig {
    icon: any;
    colors: {
        text: string;
        bg: string;
        focus: string;
    };
    brandColor?: string;
}

export const SOCIAL_PROVIDER_UI_MAP: Record<string, ProviderUIConfig> = {
    google: {
        icon: IconGoogle,
        colors: {
            text: 'text-white dark:text-gray-900',
            bg: 'bg-gray-900 border border-gray-900 hover:bg-gray-800 dark:bg-white dark:border-gray-300 dark:hover:bg-gray-50',
            focus: 'focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-blue-500 dark:focus:ring-offset-gray-800',
        },
        brandColor: '#4285f4',
    },
    github: {
        icon: IconGithub,
        colors: {
            text: 'text-white',
            bg: 'bg-gray-900 hover:bg-gray-800 border border-gray-900',
            focus: 'focus:ring-2 focus:ring-gray-500 focus:ring-offset-2',
        },
        brandColor: '#333333',
    },
    facebook: {
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
