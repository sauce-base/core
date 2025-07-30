<script setup lang="ts">
import { useField } from 'vee-validate';
import { computed, provide, toRef } from 'vue';

const props = defineProps<{
    name: string;
}>();

const { errorMessage, value, handleChange, handleBlur, meta } = useField(
    () => props.name,
    undefined,
    {
        validateOnValueUpdate: false,
    },
);

const fieldContext = {
    name: toRef(props, 'name'),
    id: computed(() => props.name),
    errorMessage,
    value,
    meta,
};

provide('fieldContext', fieldContext);

// This is what shadcn-vue calls "componentField"
const componentField = computed(() => ({
    modelValue: value.value,
    'onUpdate:modelValue': (val: any) => handleChange(val),
    onBlur: handleBlur,
    id: props.name,
    name: props.name,
}));
</script>

<template>
    <div>
        <slot :componentField="componentField" />
    </div>
</template>
