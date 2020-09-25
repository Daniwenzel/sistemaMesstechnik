require('./misc');
require('./off-canvas');
require('./login');
require('./password');
require('./toggle-graph');
require('./rcode');
require('./hover-tabs');

window.Plotly = require('plotly.js/dist/plotly-basic');

window.Swal = require('sweetalert2');
//window.Chart = require('chart.js');

window.Vue = require('vue');
// window.barbaPrefetch = require('@barba/prefetch');
//window.toastr = require('./../../node_modules/toastr/toastr');
// window.Daterangepicker = require('daterangepicker');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('site-card', require('./components/SiteCard.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    mounted() {
        console.log('app mounted');
    }
});