import './../../common/js/bootstrap';
import Vue from 'vue';
import error2message from './../../common/js/error2message';
import api from './../../common/js/api';
import './../../common/js/common';
import moment from 'moment';
import 'moment/locale/zh-cn';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';

window.moment = moment;
Vue.use(require('vue-axios'), window.axios);
Vue.use(require('element-ui'));
Object.defineProperty(Vue.prototype, '$moment', {value: moment});
Vue.component('datePicker', datePicker);
Object.defineProperty(Vue.prototype, '$error2message', {value: error2message});
Object.defineProperty(Vue.prototype, '$api', {value: api()});
Vue.filter('formatNum', function (value, point) {
    return parseFloat(value || 0).format(point || 0);
});

new Vue({
    render: h => h(require('./components/Login.vue')),
}).$mount('#app');
