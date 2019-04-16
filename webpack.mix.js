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
// .js('resources/js/off-canvas.js', 'public/js/off-canvas.js')
//.js('resources/js/misc.js', 'public/js/misc.js')
//.js('resources/js/dashboard.js', 'public/js/dashboard.js')
    .sass('vendor/iconfonts/font-awesome/scss/font-awesome.scss', 'public/css')
    .js('node_modules/sweetalert2/dist/sweetalert2.all.js', 'public/js')
    .js('node_modules/sweetalert2/dist/sweetalert2.js', 'public/js')
    //.js('resources/js/mask.js', 'public/js/mask.js')
    //.js('node_modules/popper.js/dist/popper.js', 'public/js')
    //.js('node_modules/jquery/src/jquery.js', 'public/js')
    //.js('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js')
    //.js('vendor/igorescobar/jquery-mask-plugin/src/jquery.mask.js', 'public/js/jquery-mask.js')
    .sass('resources/scss/style.scss', 'public/css')
    .sass('node_modules/sweetalert2/src/sweetalert2.scss', 'public/css')
    .sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/css/bootstrap.css')
    //.copy('vendor/js/vendor.bundle.addons.js', 'public/js/vendor.bundle.addons.js')
    //.copy('vendor/js/vendor.bundle.base.js', 'public/js/vendor.bundle.base.js')
    .styles(['vendor/iconfonts/mdi/css/materialdesignicons.min.css',
        'vendor/iconfonts/puse-icons-feather/feather.css',
        'vendor/css/vendor.bundle.base.css',
        'vendor/css/vendor.bundle.addons.css'], 'public/css/all.css')
    .copy(['node_modules/@mdi/font/fonts'], 'public/fonts')
    .copy('resources/images', 'public/images');


/*'public/css/materialdesignicons.min.css')
'public/css/feather.css')
'public/css/vendor.bundle.base.css')
 'public/css/vendor.bundle.addons.css')
 */