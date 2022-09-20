import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import FullReload from 'vite-plugin-full-reload';

// noinspection JSUnusedGlobalSymbols
export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        FullReload(['app/Actions/**/*', 'app/Http/Middleware/HandleInertiaRequests.php'], { delay: 200 })
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
});
