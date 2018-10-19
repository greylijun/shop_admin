let mix = require('laravel-mix');

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

mix.sass('resources/assets/common/sass/bootstrap.scss', 'public/css')

    .js('resources/assets/admin/js/admin.js', 'public/js/admin.js')
    .sass('resources/assets/admin/sass/admin.scss', 'public/css/admin.css')
    .js('resources/assets/admin/js/login.js', 'public/js/admin.login.js')
    .sass('resources/assets/admin/sass/login.scss', 'public/css/admin.login.css')

    .extract(['babel-polyfill', 'jquery', 'jquery-ui', 'vue', 'vue-bus', 'axios', 'vue-axios', 'vue-router', 'element-ui',
        'moment', 'moment/locale/zh-cn', 'nprogress', 'qs',
        'vue-bootstrap-datetimepicker'])
    .version();
