<script setup lang="ts">
import { computed, toRefs } from 'vue';
import { type SocialProvider } from '../composables/useSocialLogin';

interface Props {
    providerKey: string;
    providerConfig: SocialProvider;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});

const { providerKey, providerConfig, disabled } = toRefs(props);

const buttonClasses = computed(() => [
    'flex w-full items-center justify-center gap-3 rounded-md px-4 py-2.5 text-sm font-medium transition-colors duration-200',
    providerConfig.value.ui.colors.bg,
    providerConfig.value.ui.colors.text,
    providerConfig.value.ui.colors.focus,
    'disabled:cursor-not-allowed disabled:opacity-50',
    'shadow-sm',
]);

const loginUrl = computed(() =>
    route('auth.social.redirect', {
        provider: providerKey.value,
    }),
);
</script>

<template>
    <a
        role="button"
        :disabled="disabled"
        :href="loginUrl"
        :class="buttonClasses"
        @click="disabled && $event.preventDefault()"
    >
        <component :is="providerConfig.ui.icon" class="h-5 w-5" />

        <span>Continue with {{ providerConfig.name }}</span>
    </a>
</template>
