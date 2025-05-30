import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel([
            'resources/sass/app.scss',
            'resources/sass/admin.scss',
            'resources/js/app.js',
        ]),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/bootstrap-icons/font/*.woff2',
                    dest: 'assets/fonts'
                },
                {
                    src: 'node_modules/bootstrap-icons/font/*.woff',
                    dest: 'assets/fonts'
                },
                {
                    src: 'node_modules/bootstrap-icons/font/bootstrap-icons.min.css',
                    dest: 'assets/css'
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
