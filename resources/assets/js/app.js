
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('room-component', require('./components/RoomComponent.vue'));
Vue.component('stats-component', require('./components/StatsComponent.vue'));
Vue.component('login-component', require('./components/login/LoginComponent.vue'));
Vue.component('password-reset-component', require('./components/login/passwordResetComponent.vue'));
Vue.component('password-forget-component', require('./components/login/passwordForgetComponent.vue'));

const app = new Vue({
    el: '#app'
});
