require('./bootstrap');
import Router from 'vue-router'
import Vue from 'vue'
import App from './App.vue'
import axios from 'axios'
import routes from './routes/index'
import BootstrapVue from 'bootstrap-vue'
import "bootstrap-vue/dist/bootstrap-vue.css"
import {BootstrapVueIcons} from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'



Vue.use(BootstrapVueIcons)
Vue.use(BootstrapVue)
Vue.use(Router)

 const app = new Vue({
    el: '#app',
    router: routes,
    components: {App},
    template: '<App />'
});

