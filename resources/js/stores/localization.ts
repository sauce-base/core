import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

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
