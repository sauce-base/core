<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link';
        size?: 'default' | 'sm' | 'lg' | 'icon';
        type?: 'button' | 'submit' | 'reset';
        disabled?: boolean;
        asChild?: boolean;
    }>(),
    {
        variant: 'default',
        size: 'default',
        type: 'button',
        disabled: false,
        asChild: false,
    },
);

const buttonClasses = computed(() => {
    const baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
    
    const variants = {
        default: 'bg-gray-900 text-gray-50 hover:bg-gray-900/90 dark:bg-gray-50 dark:text-gray-900 dark:hover:bg-gray-50/90',
        destructive: 'bg-red-500 text-gray-50 hover:bg-red-500/90 dark:bg-red-900 dark:text-gray-50 dark:hover:bg-red-900/90',
        outline: 'border border-gray-200 bg-white hover:bg-gray-100 hover:text-gray-900 dark:border-gray-800 dark:bg-gray-950 dark:hover:bg-gray-800 dark:hover:text-gray-50',
        secondary: 'bg-gray-100 text-gray-900 hover:bg-gray-100/80 dark:bg-gray-800 dark:text-gray-50 dark:hover:bg-gray-800/80',
        ghost: 'hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-gray-50',
        link: 'text-gray-900 underline-offset-4 hover:underline dark:text-gray-50',
    };
    
    const sizes = {
        default: 'h-10 px-4 py-2',
        sm: 'h-9 rounded-md px-3',
        lg: 'h-11 rounded-md px-8',
        icon: 'h-10 w-10',
    };
    
    return `${baseClasses} ${variants[props.variant]} ${sizes[props.size]}`;
});
</script>

<template>
    <button
        v-if="!asChild"
        :type="type"
        :disabled="disabled"
        :class="buttonClasses"
    >
        <slot />
    </button>
    <component
        v-else
        :is="'div'"
        :class="buttonClasses"
    >
        <slot />
    </component>
</template>