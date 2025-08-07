import { defineStore } from 'pinia';
import { computed, ref, watch } from 'vue';

export type Theme = 'light' | 'dark' | 'system';

export const useThemeStore = defineStore(
    'theme',
    () => {
        const theme = ref<Theme>('system');

        const mediaQuery =
            typeof window !== 'undefined'
                ? window.matchMedia('(prefers-color-scheme: dark)')
                : null;

        const isDark = computed(() => {
            if (theme.value === 'system') {
                return mediaQuery?.matches || false;
            }
            return theme.value === 'dark';
        });

        const applyTheme = () => {
            if (typeof document === 'undefined') return;

            const html = document.documentElement;

            // Remove existing theme classes first
            html.classList.remove('light', 'dark');

            if (isDark.value) {
                html.classList.add('dark');
                html.style.backgroundColor = '#030712'; // gray-950
            } else {
                html.classList.add('light');
                html.style.backgroundColor = '#ffffff'; // white
            }
        };

        const setTheme = (newTheme: Theme) => {
            theme.value = newTheme;
            applyTheme();
        };

        // Initialize theme on store creation
        const initTheme = () => {
            // Ensure theme is a valid string value
            if (!['light', 'dark', 'system'].includes(theme.value)) {
                theme.value = 'system';
            }

            applyTheme();

            // Watch for system theme changes
            if (mediaQuery) {
                mediaQuery.addEventListener('change', applyTheme);
            }
        };

        // Initialize immediately if possible
        if (typeof window !== 'undefined') {
            initTheme();

            // Watch for any changes to isDark and apply theme
            watch(isDark, applyTheme, { immediate: true });

            // Also watch theme changes directly
            watch(theme, applyTheme);
        }

        return {
            theme,
            isDark,
            setTheme,
            initTheme,
        };
    },
    {
        persist: true,
    },
);
