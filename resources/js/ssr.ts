import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { i18nVue } from 'laravel-vue-i18n';
import { createSSRApp, h } from 'vue';
import { renderToString } from 'vue/server-renderer';
import { ZiggyVue } from 'ziggy-js';
import { resolveLanguage, resolveModularPageComponent } from './lib/utils';

const appName = import.meta.env.VITE_APP_NAME || 'Sauce Base';

createServer(
    (page) =>
        createInertiaApp({
            page,
            render: renderToString,
            title: (title) => `${title} - ${appName}`,
            resolve: resolveModularPageComponent,
            setup({ App, props, plugin }) {
                const app = createSSRApp({ render: () => h(App, props) })
                    .use(plugin)
                    .use(i18nVue, {
                        resolve: resolveLanguage,
                    })
                    .use(ZiggyVue);

                return app;
            },
        }),
    { cluster: true },
);
