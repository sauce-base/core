import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

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
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
