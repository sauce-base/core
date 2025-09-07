<script setup lang="ts">
import { type SocialProvider } from '../composables/useSocialLogin';

interface Props {
    providerKey: string;
    providerConfig: SocialProvider;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});

const handleLogin = () => {
    if (props.disabled) return;
    window.location.href = route('auth.social.redirect', {
        provider: props.providerKey,
    });
};
</script>

<template>
    <button
        type="button"
        :disabled="disabled"
        :class="[
            'flex w-full items-center justify-center gap-3 rounded-md px-4 py-2.5 text-sm font-medium transition-colors duration-200',
            providerConfig.ui.colors.bg,
            providerConfig.ui.colors.text,
            providerConfig.ui.colors.focus,
            'disabled:cursor-not-allowed disabled:opacity-50',
            'shadow-sm',
        ]"
        @click="handleLogin"
    >
        <component :is="providerConfig.ui.icon" class="h-5 w-5" />

        <span>Continue with {{ providerConfig.name }}</span>
    </button>
</template>
