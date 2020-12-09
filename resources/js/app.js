require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';

import Notifications from 'vue-notification'
 
Vue.use(Notifications)

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/* 
 * load in the components
 */
const files = require.context('./', true, /.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const app = new Vue({
  el: '#app'
});
