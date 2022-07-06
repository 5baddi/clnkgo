require('./bootstrap');

window.Vue = require("vue").default;

Vue.component('statistic-card', require('./components/statisticCard.vue').default);

const app = new Vue({
    el: '#app',
});