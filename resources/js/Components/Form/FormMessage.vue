<script setup lang="ts">
import { inject } from 'vue';

withDefaults(
    defineProps<{
        message?: string;
    }>(),
    {},
);

const fieldContext = inject('fieldContext', null);
</script>

<template>
    <p
        v-if="message || fieldContext?.errorMessage.value"
        :id="
            fieldContext?.id.value
                ? `${fieldContext.id.value}-message`
                : undefined
        "
        class="text-sm font-medium text-red-600 dark:text-red-400"
    >
        {{ message || fieldContext?.errorMessage.value }}
    </p>
</template>
