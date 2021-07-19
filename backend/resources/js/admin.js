require('./bootstrap');
require('overlayscrollbars');
require('datatables.net-bs4');
require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');
require('./common');

window.Vue = require('vue');

const app = new Vue({
  el: '#app',
});
