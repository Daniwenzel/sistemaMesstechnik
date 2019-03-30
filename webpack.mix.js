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

mix.js([
    'resources/js/app.js',
    'resources/js/off-canvas.js',
    'resources/js/misc.js',
    'resources/js/dashboard.js',
   ], 'public/js/all.js')
   .sass('resources/sass/app.scss', 'public/css/sass.css')
   .styles([
       'resources/css/style.css',
       'vendor/iconfonts/mdi/css/materialdesignicons.min.css',
       'vendor/css/vendor.bundle.base.css',
       'vendor/css/vendor.bundle.addons.css'
       ], 'public/css/all.css')
    .copy(['node_modules/@mdi/font/fonts'], 'public/fonts')
    .copy('resources/images', 'public/images');
