import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel(['resources/sass/app.scss', 'resources/sass/admin.scss', 'resources/js/app.js']),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/bootstrap-icons/font/*',
                    dest: 'assets/fonts'
                }
            ]
        })
    ],
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
