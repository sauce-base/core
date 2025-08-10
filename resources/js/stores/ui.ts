import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useUIStore = defineStore(
    'ui',
    () => {
        const sidebarOpen = ref(true);
        const language = ref('en');

        const toggleSidebar = () => {
            sidebarOpen.value = !sidebarOpen.value;
        };

        const setSidebarOpen = (open: boolean) => {
            sidebarOpen.value = open;
        };

        const setLanguage = (lang: string) => {
            language.value = lang;
        };

        return {
            sidebarOpen,
            language,
            toggleSidebar,
            setSidebarOpen,
            setLanguage,
        };
    },
    {
        persist: true,
    },
);
