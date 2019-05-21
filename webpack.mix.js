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
    .sass('vendor/iconfonts/font-awesome/scss/font-awesome.scss', 'public/css')
    .copy('resources/js/alerts.js', 'public/js')
    .copy('resources/js/chart.js', 'public/js')
    .copy('resources/js/password.js', 'public/js')
    .sass('resources/scss/style.scss', 'public/css')
    .sass('node_modules/sweetalert2/src/sweetalert2.scss', 'public/css')
    .sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .styles(['vendor/iconfonts/mdi/css/materialdesignicons.min.css',
        'vendor/iconfonts/puse-icons-feather/feather.css',
        'vendor/css/vendor.bundle.base.css',
        'vendor/css/vendor.bundle.addons.css',
        'node_modules/animate.css/animate.min.css'], 'public/css/all.css')
    .copy(['node_modules/@mdi/font/fonts'], 'public/fonts')
    .copy('resources/images', 'public/images');