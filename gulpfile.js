const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
        .webpack('app.js')
        .webpack('shipment-requests.js')
        .webpack('shipment-requests-backend.js')
        .webpack('procurement-tickets-new.js')
        .webpack('procurement-tickets-backend.js');

    mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

    mix.version([
        'css/app.css',
        'js/app.js',
        'js/shipment-requests.js',
        'js/shipment-requests-backend.js',
        'js/procurement-tickets-new.js',
        'js/procurement-tickets-backend.js',
    ]);
});
