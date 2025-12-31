import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import Icons from 'unplugin-icons/vite';
import { defineConfig } from 'vite';

async function createConfig() {
    const sslKeyPath = 'docker/ssl/app.key.pem';
    const sslCertPath = 'docker/ssl/app.pem';
    const hasSSL = fs.existsSync(sslKeyPath) && fs.existsSync(sslCertPath);

    return defineConfig({
        server: hasSSL
            ? {
                  https: {
                      key: fs.readFileSync(sslKeyPath),
                      cert: fs.readFileSync(sslCertPath),
                  },
              }
            : {},
        plugins: [
            laravel({
                input: 'resources/js/app.ts',
                refresh: true,
                ssr: 'resources/js/ssr.ts', //TODO: make SSR compatible with the modular application.
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            Icons({
                compiler: 'vue3',
                autoInstall: true,
            }),
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js'),
                '@modules': path.resolve(__dirname, 'modules'),
                'ziggy-js': path.resolve(__dirname, 'vendor/tightenco/ziggy'),
            },
        },
    });
}

export default createConfig();
