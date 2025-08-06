import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath } from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Export paths for the main Vite config to use
export const paths = [
    'modules/Auth/resources/assets/css/app.css',
    'modules/Auth/resources/assets/js/app.ts',
];

export default defineConfig({
    build: {
        outDir: '../../public/build-auth',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-auth',
            input: [
                path.resolve(__dirname, 'resources/assets/css/app.css'),
                path.resolve(__dirname, 'resources/assets/js/app.ts'),
            ],
            refresh: true,
        }),
    ],
});
