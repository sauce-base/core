<script setup lang="ts">
import type { FieldContext } from '@/types/form';
import { computed, inject } from 'vue';

const props = withDefaults(
    defineProps<{
        type?: string;
        placeholder?: string;
        disabled?: boolean;
        error?: string;
    }>(),
    {
        type: 'text',
    },
);

const model = defineModel<string | unknown>();
const fieldContext = inject<FieldContext | null>('fieldContext', null);

const hasError = computed(() => {
    return !!(props.error || fieldContext?.errorMessage.value);
});
</script>

<template>
    <input
        v-model="model"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        :data-testid="fieldContext?.id?.value || 'input'"
        :class="[
            'w-full rounded-md border bg-white px-3 py-2 text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-400',
            hasError
                ? 'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-600 dark:focus:border-red-500 dark:focus:ring-red-500'
                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:focus:border-indigo-400 dark:focus:ring-indigo-400',
        ]"
    />
</template>
