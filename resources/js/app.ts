import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
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
            .use(ZiggyVue);

        // Initialize middleware after app setup
        setupMiddleware();

        // Initialize global theme persistence after mount for proper Vue reactivity
        useColorMode({ emitAuto: true });

        // Mount the app
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
