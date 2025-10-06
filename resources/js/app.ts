import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { useLocalizationStore } from '@modules/Localization/resources/js/stores';
import { useColorMode } from '@vueuse/core';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { resolveLanguage, resolveModularComponent } from './lib/utils';
import { setupMiddleware } from './middleware';
import { pinia } from './stores';

const appName = import.meta.env.VITE_APP_NAME || 'Sauce Base';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: resolveModularComponent,
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

        // Initialize language from store after app is mounted
        const localizationStore = useLocalizationStore();
        if (localizationStore.language !== 'en') {
            loadLanguageAsync(localizationStore.language);
        }

        // Initialize global theme persistence after mount for proper Vue reactivity
        useColorMode({ emitAuto: true });

        // Mount the app
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
