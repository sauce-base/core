<script setup lang="ts">
// Backward compatibility wrapper for SecondaryButton
// TODO: Migrate to @/Components/ui/Button with variant="secondary"
import Button from '@/Components/ui/Button.vue';

withDefaults(
    defineProps<{
        type?: 'button' | 'submit' | 'reset';
        disabled?: boolean;
    }>(),
    {
        type: 'button',
        disabled: false,
    },
);
</script>

<template>
    <Button variant="secondary" :type="type" :disabled="disabled">
        <slot />
    </Button>
</template>
