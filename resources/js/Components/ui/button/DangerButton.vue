<script setup lang="ts">
// Backward compatibility wrapper for DangerButton
// TODO: Migrate to @/Components/ui/Button with variant="danger"
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
    <Button variant="danger" :type="type" :disabled="disabled">
        <slot />
    </Button>
</template>
