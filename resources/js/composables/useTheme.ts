import { computed, ref, watch } from 'vue';

type Theme = 'light' | 'dark' | 'system';

export function useTheme() {
    const theme = ref<Theme>('system');
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

    const isDark = computed(() => {
        if (theme.value === 'system') {
            return mediaQuery.matches;
        }
        return theme.value === 'dark';
    });

    const applyTheme = () => {
        const html = document.documentElement;
        if (isDark.value) {
            html.classList.add('dark');
            html.style.backgroundColor = '#030712'; // gray-950
        } else {
            html.classList.remove('dark');
            html.style.backgroundColor = '#ffffff'; // white
        }
    };

    const setTheme = (newTheme: Theme) => {
        theme.value = newTheme;
        localStorage.setItem('tadone-theme', newTheme);
        applyTheme();
    };

    // Initialize theme from localStorage or default to system
    const initTheme = () => {
        const stored = localStorage.getItem('tadone-theme') as Theme;
        if (stored && ['light', 'dark', 'system'].includes(stored)) {
            theme.value = stored;
        }
        applyTheme();
    };

    // Initialize theme immediately if possible, otherwise on mount
    if (typeof window !== 'undefined') {
        initTheme();
    }

    // Watch for theme changes
    watch(theme, applyTheme);

    // Watch for system theme changes
    mediaQuery.addEventListener('change', applyTheme);

    return {
        theme,
        setTheme,
        isDark,
    };
}
