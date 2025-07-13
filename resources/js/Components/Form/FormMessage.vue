<script setup lang="ts">
import type { FieldContext } from '@/types/form';
import { computed, inject } from 'vue';

const props = withDefaults(
    defineProps<{
        message?: string;
        inertiaError?: string;
    }>(),
    {},
);

const fieldContext = inject<FieldContext | null>('fieldContext', null);

const displayMessage = computed(() => {
    return (
        props.inertiaError || props.message || fieldContext?.errorMessage.value
    );
});
</script>

<template>
    <p
        v-if="displayMessage"
        :id="
            fieldContext?.id.value
                ? `${fieldContext.id.value}-message`
                : undefined
        "
        :data-testid="
            fieldContext?.id.value
                ? `${fieldContext.id.value}-error`
                : 'form-error'
        "
        class="text-sm font-medium text-red-600 dark:text-red-400"
    >
        {{ displayMessage }}
    </p>
</template>
