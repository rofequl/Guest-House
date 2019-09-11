require('./bootstrap');
window.Vue = require('vue');
import moment from 'moment';
import VueRouter from 'vue-router';
import {routes} from './routes';
import Chart from 'chart.js';
import Popper from 'popper.js';
import {Form, HasError, AlertError} from 'vform'
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import VueProgressBar from 'vue-progressbar'
import swal from 'sweetalert2'
import Select2 from 'v-select2-component';
import Print from 'vue-print-nb'

Vue.use(Print);
Vue.component('Select2', Select2);
Vue.use(PerfectScrollbar);
Vue.use(VueRouter);
Vue.use(datePicker);


const router = new VueRouter({
    routes,
    mode: 'history'
});

Vue.use(VueProgressBar, {
    color: 'orange',
    failedColor: '#333',
    height: '5px'
});


const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;
window.Form = Form;
window.swal = swal;
window.Fire = new Vue();

Vue.component('main-component', require('./components/MainApp').default);
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
    router,
    ready: function () {
        this.fetchTips();
    },
    methods: {
        fetchTips: function () {
            this.$http.get('/api/tips', function (tips) {
                this.$set('tips', tips)
            });
        }

    }
});


jQuery.extend(true, jQuery.fn.datetimepicker.defaults, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'fas fa-calendar-check',
        clear: 'far fa-trash-alt',
        close: 'far fa-times-circle'
    }
});


