const mix = require('laravel-mix');

mix.sass('app.scss', 'public/css/app.css');

mix.vue();
mix.js('app.js', 'public/js/app.js');
mix.js('shipment-requests.js', 'public/js/shipment-requests.js');
mix.js('shipment-requests-backend.js', 'public/js/shipment-requests-backend.js');

mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

mix.version();
