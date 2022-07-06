require('./bootstrap');

import Vue from 'vue';

Vue.component('statistic-card', require('./components/statisticCard.vue'));

const app = new Vue({
    el: '#app',
});