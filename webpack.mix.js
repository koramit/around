const mix = require('laravel-mix');
const LiveReloadPlugin = require('webpack-livereload-plugin');

mix.js('resources/js/app.js', 'public/js')
    .vue({ version: 3, runtimeOnly: true })
    .extract()
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ])
    .webpackConfig({
        plugins: [new LiveReloadPlugin()]
    })
    .version()
    .disableSuccessNotifications();
