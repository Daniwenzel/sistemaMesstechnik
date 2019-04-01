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

mix.js('resources/js/app.js', 'public/js/app.js')
    .js('resources/js/off-canvas.js', 'public/js/off-canvas.js')
    .js('resources/js/misc.js', 'public/js/misc.js')
    .js('resources/js/dashboard.js', 'public/js/dashboard.js')
    .copy('vendor/js/vendor.bundle.addons.js', 'public/js/vendor.bundle.addons.js')
    .copy('vendor/js/vendor.bundle.base.js', 'public/js/vendor.bundle.base.js')
    .styles([
       'resources/css/style.css',
       'vendor/iconfonts/mdi/css/materialdesignicons.min.css',
       'vendor/iconfonts/puse-icons-feather/feather.css',
       ], 'public/css/all.css')
    .copy('vendor/css/vendor.bundle.base.css', 'public/css/vendor.bundle.base.css')
    .copy('vendor/css/vendor.bundle.addons.css', 'public/css/vendor.bundle.addons.css')
    .copy(['node_modules/@mdi/font/fonts'], 'public/fonts')
    .copy('resources/images', 'public/images');
