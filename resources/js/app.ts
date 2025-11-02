import { useLocalizationStore } from '@/stores/localization';
import { createInertiaApp } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { resolveLanguage, resolveModularPageComponent } from './lib/utils';
import { pinia } from './stores';

import {
    discoverModuleSetups,
    executeAfterMountCallbacks,
    executeModuleSetups,
} from './lib/moduleSetup';

import '../css/app.css';

const appName = import.meta.env.VITE_APP_NAME || 'Sauce Base';
const moduleSetups = discoverModuleSetups();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: resolveModularPageComponent,
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .use(i18nVue, {
                resolve: resolveLanguage,
            });

        // Execute module setup functions and collect afterMount callbacks
        executeModuleSetups(app, moduleSetups).then((afterMountCallbacks) => {
            // Initialize global theme persistence after mount for proper Vue reactivity
            useColorMode();

            const { language } = useLocalizationStore();
            if (language !== 'en') {
                loadLanguageAsync(language);
            }

            // Mount the app
            app.mount(el);

            // Execute module afterMount callbacks
            executeAfterMountCallbacks(afterMountCallbacks);
        });
    },
    progress: {
        color: '#4B5563',
    },
});
