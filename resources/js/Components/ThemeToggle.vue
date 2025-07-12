<script setup lang="ts">
import IconMonitor from '~icons/heroicons/computer-desktop';
import IconMoon from '~icons/heroicons/moon';
import IconSun from '~icons/heroicons/sun';
import { useTheme } from '../Composables/useTheme';

const { theme, setTheme } = useTheme();

const toggleTheme = () => {
    const themes = ['light', 'dark', 'system'] as const;
    const currentIndex = themes.indexOf(theme.value);
    const nextIndex = (currentIndex + 1) % themes.length;
    const nextTheme = themes[nextIndex];

    setTheme(nextTheme);
};
</script>

<template>
    <button
        @click="toggleTheme"
        class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 dark:focus:ring-offset-gray-950"
        :title="`Current theme: ${theme}`"
    >
        <IconSun v-if="theme === 'light'" class="h-5 w-5" />
        <IconMoon v-else-if="theme === 'dark'" class="h-5 w-5" />
        <IconMonitor v-else class="h-5 w-5" />
    </button>
</template>
