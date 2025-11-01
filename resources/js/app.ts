import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { i18nVue } from 'laravel-vue-i18n';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import {
    resolveLanguage,
    resolveModularPageComponent
} from './lib/utils';
import { setupMiddleware } from './middleware';
import { pinia } from './stores';

const appName = import.meta.env.VITE_APP_NAME || 'Sauce Base';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: resolveModularPageComponent,
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(i18nVue, {
                resolve: resolveLanguage,
            })
            .use(ZiggyVue);

        // Initialize middleware after app setup
        setupMiddleware();

        /**
         * Uncomment the lines below if you have the localization module installed
         * and want to initialize the language from the store
         */
        // Initialize language from store after app is mounted
        // const { language } = useLocalizationStore();
        // if (language !== 'en') {
        //     loadLanguageAsync(language);
        // }

        // Initialize global theme persistence after mount for proper Vue reactivity
        useColorMode();

        // Mount the app
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
