const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/dist/css');
mix.options({ processCssUrls: false })

mix.vue();
mix.js('resources/js/app.js', 'public/dist/js');
mix.js('resources/js/shipment-requests.js', 'public/dist/js');
mix.js('resources/js/shipment-requests-backend.js', 'public/dist/js');

mix.copy('node_modules/font-awesome/fonts', 'public/dist/fonts');

mix.version();
