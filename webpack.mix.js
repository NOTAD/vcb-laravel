// eslint-disable-next-line no-undef
const mix = require('laravel-mix');
// eslint-disable-next-line no-undef
const path = require('path');
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
mix.webpackConfig({
    resolve: {
        alias: {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
        },
    },
});

// eslint-disable-next-line no-undef
if (process.env.NODE_ENV === 'development') {
    mix.sourceMaps();
}
mix.js('resources/js/admin_app.js', 'public/js/admin')
    .js('resources/js/shop_app.js', 'public/js')
    .js('resources/js/auth_app.js', 'public/js')
    .sass('resources/sass/shop_app.scss', 'public/css')
    .sass('resources/sass/auth_app.scss', 'public/css')
    .sass('resources/sass/error_app.scss', 'public/css')
    .sass('resources/sass/admin_app.scss', 'public/css')
    .copy('resources/js/vendor', 'public/js/vendor')
    .copy('resources/images', 'public/images')
    .copy('resources/sass/vendor', 'public/css/vendor')
    .copy('node_modules/element-ui/lib/theme-chalk/index.css', 'public/css/vendor/admin/element-ui.css')
    .copy('node_modules/element-ui/lib/theme-chalk/fonts', 'public/css/vendor/admin/fonts')
    .version();
