<script setup lang="ts">
import IconGoogle from '~icons/mdi/google';

type SocialProvider = 'google';

interface ProviderConfig {
    name: string;
    icon: any;
    textColor: string;
    bgColor: string;
    focusRing: string;
}

interface Props {
    provider: SocialProvider;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});

const providerConfig: Record<SocialProvider, ProviderConfig> = {
    google: {
        name: 'Google',
        icon: IconGoogle,
        textColor: 'text-white dark:text-gray-900',
        bgColor: 'bg-gray-900 border border-gray-900 hover:bg-gray-800 dark:bg-white dark:border-gray-300 dark:hover:bg-gray-50',
        focusRing: 'focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-blue-500 dark:focus:ring-offset-gray-800',
    },
};

const config = providerConfig[props.provider];

const handleLogin = () => {
    if (props.disabled) return;
    window.location.href = `/auth/${props.provider}`;
};
</script>

<template>
    <button
        type="button"
        :disabled="disabled"
        :class="[
            'flex w-full items-center justify-center gap-3 rounded-md px-4 py-2.5 text-sm font-medium transition-colors duration-200',
            config.bgColor,
            config.textColor,
            config.focusRing,
            'disabled:cursor-not-allowed disabled:opacity-50',
            'shadow-sm',
        ]"
        @click="handleLogin"
    >
        <component :is="config.icon" class="h-5 w-5" />

        <span>Continue with {{ config.name }}</span>
    </button>
</template>
