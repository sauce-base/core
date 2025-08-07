<script setup lang="ts">
import { useThemeStore } from '@/stores/theme';
import IconMonitor from '~icons/heroicons/computer-desktop';
import IconMoon from '~icons/heroicons/moon';
import IconSun from '~icons/heroicons/sun';

const themeStore = useThemeStore();

const toggleTheme = () => {
    switch (themeStore.theme) {
        case 'light':
            themeStore.setTheme('dark');
            break;
        case 'dark':
            themeStore.setTheme('system');
            break;
        case 'system':
            themeStore.setTheme('light');
            break;
    }
};
</script>

<template>
    <button
        @click="toggleTheme"
        class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 dark:focus:ring-offset-gray-950"
        :title="`Current theme: ${themeStore.theme}`"
    >
        <IconSun v-if="themeStore.theme === 'light'" class="h-5 w-5" />
        <IconMoon v-else-if="themeStore.theme === 'dark'" class="h-5 w-5" />
        <IconMonitor v-else class="h-5 w-5" />
    </button>
</template>
