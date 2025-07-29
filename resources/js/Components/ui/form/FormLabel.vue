<script setup lang="ts">
import type { FieldContext } from '@/types/form';
import { computed, inject } from 'vue';

const props = withDefaults(
    defineProps<{
        required?: boolean;
        error?: string;
    }>(),
    {
        required: false,
    },
);

const fieldContext = inject<FieldContext | null>('fieldContext', null);

const hasError = computed(() => {
    return !!(props.error || fieldContext?.errorMessage.value);
});
</script>

<template>
    <label
        :for="fieldContext?.id?.value"
        :class="[
            'text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
            hasError
                ? 'text-red-600 dark:text-red-400'
                : 'text-gray-900 dark:text-gray-100',
        ]"
    >
        <slot />
        <span v-if="required" class="ml-1 text-red-500">*</span>
    </label>
</template>
