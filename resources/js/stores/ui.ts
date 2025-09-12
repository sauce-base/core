import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useUIStore = defineStore(
    'ui',
    () => {
        const sidebarOpen = ref(true);

        const toggleSidebar = () => {
            sidebarOpen.value = !sidebarOpen.value;
        };

        const setSidebarOpen = (open: boolean) => {
            sidebarOpen.value = open;
        };

        return {
            sidebarOpen,
            toggleSidebar,
            setSidebarOpen,
        };
    },
    {
        persist: true,
    },
);
