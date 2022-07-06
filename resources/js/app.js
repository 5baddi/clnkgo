require('./bootstrap');

window.Vue = require('vue').default;
window.VueResource = require('vue-resource');
window.Vue.use(window.VueResource);

Vue.component('statistic-card', require('./components/statisticCard.vue'));

const app = new Vue({
    el: '#app',
});