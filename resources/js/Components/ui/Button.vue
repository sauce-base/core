<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        variant?: 'primary' | 'secondary' | 'danger';
        type?: 'button' | 'submit' | 'reset';
        disabled?: boolean;
    }>(),
    {
        variant: 'primary',
        type: 'button',
        disabled: false,
    },
);

const buttonClasses = computed(() => {
    const baseClasses =
        'inline-flex items-center rounded-lg border px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:opacity-25';

    const variants = {
        primary:
            'border-transparent bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 focus:ring-indigo-500 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:active:bg-gray-300',
        secondary:
            'border-gray-300 bg-white text-gray-700 shadow-xs hover:bg-gray-50 focus:ring-indigo-500 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800',
        danger: 'border-transparent bg-red-600 text-white hover:bg-red-500 focus:bg-red-500 focus:ring-red-500 active:bg-red-700 dark:focus:ring-offset-gray-800',
    };

    return `${baseClasses} ${variants[props.variant]}`;
});
</script>

<template>
    <button :type="type" :disabled="disabled" :class="buttonClasses">
        <slot />
    </button>
</template>
