const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

// Copy plain CSS
mix.styles([
    'resources/assets/css/font-awesome-all.css',
    'resources/assets/css/flaticon.css',
    'resources/assets/css/owl.css',
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/jquery.fancybox.min.css',
    'resources/assets/css/animate.css',
    'resources/assets/css/style.css',
    'resources/assets/css/responsive.css',
], 'public/css/all.css');

// Copy JS files
mix.scripts([
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
], 'public/js/all.js');

// Copy images if needed
mix.copyDirectory('resources/assets/images', 'public/images');

mix.copyDirectory('resources/assets/fonts', 'public/fonts');


// Enable versioning for cache-busting
if (mix.inProduction()) {
    mix.version();
}
