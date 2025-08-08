<script setup lang="ts">
import { useColorMode, useCycleList } from '@vueuse/core';
import { watchEffect } from 'vue';
import IconMonitor from '~icons/heroicons/computer-desktop';
import IconMoon from '~icons/heroicons/moon';
import IconSun from '~icons/heroicons/sun';

// Use the exact pattern from VueUse demo
const mode = useColorMode({
    emitAuto: true,
});

// Use useCycleList for cycling through modes
const { state, next } = useCycleList(['light', 'dark', 'auto'] as const, {
    initialValue: mode,
});

// Sync the cycle state with color mode
watchEffect(() => (mode.value = state.value));

// Simple function to go to next mode
const toggleTheme = () => {
    next();
};
</script>

<template>
    <button
        @click="toggleTheme"
        class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 dark:focus:ring-offset-gray-950"
        :title="`Current theme: ${state}`"
    >
        <IconSun v-if="state === 'light'" class="h-5 w-5" />
        <IconMoon v-else-if="state === 'dark'" class="h-5 w-5" />
        <IconMonitor v-else class="h-5 w-5" />
    </button>
</template>
