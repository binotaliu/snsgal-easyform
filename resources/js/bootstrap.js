import axios from 'axios'
import dayjs from 'dayjs'
import Vue from 'vue'

import * as Splash from 'splash-screen'

import 'dayjs/locale/zh-tw'
dayjs.locale('zh-tw')

window.dayjs = dayjs
Vue.prototype.$dayjs = dayjs

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = Vue;

window.axios = axios
axios.defaults.headers['X-CSRF-TOKEN'] = Snsgal.csrfToken;

window.Splash = Splash;
window.moneyFormatter = require('money-formatter');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
