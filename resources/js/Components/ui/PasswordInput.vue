<script setup lang="ts">
import type { FieldContext } from '@/types/form';
import { computed, inject, ref } from 'vue';
import IconEye from '~icons/mdi/eye';
import IconEyeOff from '~icons/mdi/eye-off';

const props = withDefaults(
    defineProps<{
        placeholder?: string;
        disabled?: boolean;
        error?: string;
        id?: string;
        name?: string;
        autocomplete?: string;
    }>(),
    {
        placeholder: 'Enter your password',
    },
);

const model = defineModel<string | unknown>();
const fieldContext = inject<FieldContext | null>('fieldContext', null);
const showPassword = ref(false);

const hasError = computed(() => {
    return !!(props.error || fieldContext?.errorMessage.value);
});

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <div class="relative">
        <input
            v-model="model"
            :type="showPassword ? 'text' : 'password'"
            :placeholder="placeholder"
            :disabled="disabled"
            :id="id || fieldContext?.id?.value"
            :name="name || fieldContext?.name?.value"
            :autocomplete="autocomplete"
            :data-testid="fieldContext?.id?.value || 'password-input'"
            :class="[
                'w-full rounded-md border bg-white px-3 py-2 pr-10 text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-400',
                hasError
                    ? 'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-600 dark:focus:border-red-500 dark:focus:ring-red-500'
                    : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:focus:border-indigo-400 dark:focus:ring-indigo-400',
            ]"
        />
        <button
            type="button"
            @click="togglePasswordVisibility"
            :disabled="disabled"
            :data-testid="`${fieldContext?.id?.value || 'password'}-toggle`"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-gray-400 dark:hover:text-gray-300 dark:focus:text-gray-300"
            :title="showPassword ? 'Hide password' : 'Show password'"
        >
            <IconEye v-if="!showPassword" class="h-4 w-4" />
            <IconEyeOff v-else class="h-4 w-4" />
        </button>
    </div>
</template>
