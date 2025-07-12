<script setup lang="ts">
import { inject } from 'vue';

withDefaults(
    defineProps<{
        description?: string;
    }>(),
    {},
);

const fieldContext = inject('fieldContext', null);
</script>

<template>
    <p
        v-if="description || $slots.default"
        :id="
            fieldContext?.id.value
                ? `${fieldContext.id.value}-description`
                : undefined
        "
        class="text-sm text-gray-600 dark:text-gray-400"
    >
        <slot>{{ description }}</slot>
    </p>
</template>
