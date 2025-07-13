<script setup lang="ts">
// Backward compatibility wrapper for PrimaryButton
// TODO: Migrate to @/Components/ui/Button with variant="primary"
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
    <Button variant="primary" :type="type" :disabled="disabled">
        <slot />
    </Button>
</template>
