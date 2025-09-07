import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import Icons from 'unplugin-icons/vite';
import { defineConfig } from 'vite';
import { collectModuleAssetsPaths } from './vite-module-loader.js';

async function createConfig() {
    const paths = ['resources/js/app.ts'];
    const allPaths = await collectModuleAssetsPaths(paths, 'modules');

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
            },
        },
    });
}

export default createConfig();
