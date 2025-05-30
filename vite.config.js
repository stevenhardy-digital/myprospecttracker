import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [laravel(['resources/sass/app.scss', 'resources/sass/admin.scss', 'resources/js/app.js'])],
    assetsInclude: ['**/*.woff', '**/*.woff2'],
    build: {
        assetsInlineLimit: 0,
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name].[hash][extname]',
            },
        },
    },
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
