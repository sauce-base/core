import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

// Localization Store
export const useLocalizationStore = defineStore(
    'modules/localization',
    () => {
        const language = ref('en');

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
            // State
            language,
            // Actions
            setLanguage,
        };
    },
    {
        // Enable/Disable persistence for this store
        persist: true,
    },
);
