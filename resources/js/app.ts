import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { useLocalizationStore } from '@modules/Localization/resources/js/stores';
import { useColorMode } from '@vueuse/core';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { setupMiddleware } from './middleware';
import { pinia } from './stores';

const appName = import.meta.env.VITE_APP_NAME || 'Sauce Base';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // Handle namespace syntax (e.g., 'Auth::Index')
        if (name.includes('::')) {
            const [moduleName, componentPath] = name.split('::', 2);
            const moduleComponentPath = `../../modules/${moduleName}/resources/js/pages/${componentPath}.vue`;

            const moduleGlobs = import.meta.glob<DefineComponent>(
                '../../modules/*/resources/js/**/*.vue',
            );

            return resolvePageComponent(moduleComponentPath, moduleGlobs);
        }

        // Handle regular app pages
        return resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        );
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(i18nVue, {
                resolve: (lang: string) => {
                    const langs = import.meta.glob('../../lang/*.json', {
                        eager: true,
                    }) as Record<string, { default: any }>;
                    return langs[`../../lang/${lang}.json`]?.default || {};
                },
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
