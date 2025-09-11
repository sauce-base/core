import axios from 'axios';
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

        const setLanguage = async (lang: string) => {
            await axios
                .post(route('locale', { locale: lang }))
                .then((response) => {
                    if (response.status === 200) {
                        language.value = lang;
                    }
                })
                .catch((error) => {
                    // TODO: add proper error handling
                    console.error('Error changing language', error);
                    // Optional: show a toast/notification
                });
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
