require('./bootstrap');

// Taiwan zipcode
Vue.component('zipcode', require('./components/twzipcode.vue'));

const app = new Vue({
    el: '#app'
});
