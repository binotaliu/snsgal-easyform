import ShippingTimeSelector from './components/shipping-time-selector';
import Zipcode from './components/twzipcode';

// Taiwan zipcode
Vue.component('zipcode', Zipcode);

// Shipping Time Selector
Vue.component('shipping-time-selector', ShippingTimeSelector);

const app = new Vue({
    el: '#app'
});
