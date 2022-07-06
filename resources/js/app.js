require('./bootstrap');

let Vue = require('vue');

window.Vue = Vue;

Vue.component('statistic-card', require('./components/statisticCard.vue').default);

const app = new Vue({
    el: '#app',
});