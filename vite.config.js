import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import Icons from 'unplugin-icons/vite';
import { defineConfig } from 'vite';
import { collectModuleAssetsPaths, MODULES_PATH } from './module-loader.js';

async function createConfig() {
    const paths = ['resources/js/app.ts'];
    const allPaths = await collectModuleAssetsPaths(paths);

    return defineConfig({
        server: {
            https: {
                key: fs.readFileSync('docker/development/ssl/app.key.pem'),
                cert: fs.readFileSync('docker/development/ssl/app.pem'),
            },
        },
        plugins: [
            laravel({
                input: allPaths,
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
                '@modules': path.resolve(__dirname, MODULES_PATH),
                'ziggy-js': path.resolve(__dirname, 'vendor/tightenco/ziggy'),
            },
        },
    });
}

export default createConfig();
