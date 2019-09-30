const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/inline.js', 'public/js')
    .sass('resources/scss/style.scss', 'public/css')
    // .sass('node_modules/font-awesome/scss/font-awesome.scss', 'public/css')
    // .sass('node_modules/sweetalert2/src/sweetalert2.scss', 'public/css')
    // .sass('node_modules/@mdi/font/scss/materialdesignicons.scss', 'public/css')
    .styles([
        'public/css/font-awesome.css',
        'public/css/sweetalert2.css',
        'public/css/materialdesignicons.css'
    ], 'public/css/all.css')
    .copy('node_modules/animate.css/animate.min.css', 'public/css')
    .copy('resources/js/chart.js', 'public/js')
    .copy('resources/images', 'public/images');
