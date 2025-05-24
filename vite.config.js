import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/css/font-awesome-all.css',
                'resources/assets/css/flaticon.css',
                'resources/assets/css/owl.css',
                'resources/assets/css/bootstrap.css',
                'resources/assets/css/jquery.fancybox.min.css',
                'resources/assets/css/animate.css',
                'resources/assets/css/style.css',
                'resources/assets/css/responsive.css',
                'resources/assets/js/jquery.js',
                'resources/assets/js/popper.min.js',
                'resources/assets/js/bootstrap.min.js',
                'resources/assets/js/owl.js',
                'resources/assets/js/wow.js',
                'resources/assets/js/validation.js',
                'resources/assets/js/jquery.fancybox.js',
                'resources/assets/js/appear.js',
                'resources/assets/js/scrollbar.js',
                'resources/assets/js/jquery.paroller.min.js',
                'resources/assets/js/tilt.jquery.js',
                'resources/assets/js/script.js',
            ],
            refresh: true,
        }),
    ],
});
