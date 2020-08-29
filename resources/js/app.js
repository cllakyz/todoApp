import 'es6-promise/auto';
import axios from 'axios';
import './bootstrap';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import App from './components/App';
import auth from './auth';
import router from './routes/index';

// Set Vue
window.Vue = Vue;

// Set router
Vue.router = router;
Vue.use(VueRouter);

// Set Vue axios
Vue.use(VueAxios, axios);
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`;

// Set vue authentication
Vue.use(VueAuth, auth);

const app = new Vue({
    el: '#app',
    template: '<App />',
    components: { App },
    router
});
