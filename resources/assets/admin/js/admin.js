import './../../common/js/bootstrap';
import './../../common/js/common';
import Vue from 'vue';
import VueBus from 'vue-bus';
import VueRouter from 'vue-router';
import routes from './router.js';
import 'echarts';
import './../../common/js/macarons.js';
import moment from 'moment';
import 'moment/locale/zh-cn';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';
import error2message from './../../common/js/error2message';
import api from './../../common/js/api';

const realApi = api();
moment.locale('zh-cn');
window.moment = moment;
Vue.use(require('vue-axios'), window.axios);
Vue.use(require('element-ui'));
Vue.use(VueRouter);
Vue.component('chart', require('vue-echarts/components/ECharts.vue'));
Object.defineProperty(Vue.prototype, '$moment', {value: moment});
Vue.component('datePicker', datePicker);
Object.defineProperty(Vue.prototype, '$error2message', {value: error2message});
Object.defineProperty(Vue.prototype, '$api', {value: realApi});
const router = new VueRouter({routes});
router.beforeEach((to, from, next) => {
    if (realApi.getToken())
        next();
    else
        realApi.gotoLogin();
});
Vue.filter('formatNum', function (value, point) {
    return parseFloat(value || 0).format(point || 0);
});
Vue.use(VueBus);
new Vue({
    render: h => h(require('./App.vue')),
    router,
}).$mount('#app');