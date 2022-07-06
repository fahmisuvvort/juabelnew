const mix = require('laravel-mix');
const del = require('del');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
del('public/css')
del('public/js')
del('public/fonts')
del('public/img')
 mix
 .copy('frontend/dist/pwa/index.php', 'resources/views/app.blade.php')
 .copyDirectory('frontend/dist/pwa', 'public')
 .copy('frontend/src/index.template.php', 'public/index.php');
