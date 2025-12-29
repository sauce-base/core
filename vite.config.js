import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import Icons from 'unplugin-icons/vite';
import { defineConfig } from 'vite';
import { iconRegistryGenerator } from './vite/plugins/icon-registry';

async function createConfig() {
    return defineConfig({
        server: {
            https: {
                key: fs.readFileSync('docker/ssl/app.key.pem'),
                cert: fs.readFileSync('docker/ssl/app.pem'),
            },
        },
        plugins: [
            iconRegistryGenerator({
                scanPaths: [
                    'app/Providers', // Core ServiceProviders
                    'app/Http/Controllers', // Core Controllers (for menus)
                    'modules/*/app/Providers', // Module ServiceProviders
                    'modules/*/app/Http/Controllers', // Module Controllers (for menus)
                    'config', // Config files
                ],
                outputPath: 'resources/js/icon-registry.ts',
                debounceMs: 300,
            }),
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
