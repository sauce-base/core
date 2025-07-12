<script setup lang="ts">
import { inject } from 'vue';

withDefaults(
    defineProps<{
        required?: boolean;
    }>(),
    {
        required: false,
    },
);

const fieldContext = inject('fieldContext', null);
</script>

<template>
    <label
        :for="fieldContext?.id.value"
        :class="[
            'text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
            fieldContext?.errorMessage.value
                ? 'text-red-600 dark:text-red-400'
                : 'text-gray-900 dark:text-gray-100',
        ]"
    >
        <slot />
        <span v-if="required" class="ml-1 text-red-500">*</span>
    </label>
</template>
