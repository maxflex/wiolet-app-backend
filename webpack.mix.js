const mix = require('laravel-mix');

const webpackConfig = {
    devtool: 'inline-source-map',
    resolve: {
        alias: {
            sass: path.resolve(__dirname, 'resources/sass'),
            '@': path.resolve(__dirname, 'resources/js')
        }
    }
}

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
   .sass('resources/sass/app.scss', 'public/css')
   .webpackConfig(webpackConfig);
