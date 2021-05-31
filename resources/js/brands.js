require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import BootstrapVue from "bootstrap-vue";
import { FormFilePlugin } from 'bootstrap-vue'
import {
  ValidationObserver,
  ValidationProvider,
  extend
} from "vee-validate";
import * as rules from "vee-validate/dist/rules";

import Vue from 'vue'
window.Vue = require('vue');

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
  });

// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

Vue.use(BootstrapVue);
Vue.use(FormFilePlugin)
Vue.use(VueIziToast);

//Brands User Components
Vue.component('brands-list', require('./components/brands/index.vue').default)
Vue.component('add-brand', require('./components/brands/AddBrand.vue').default)

var app = new Vue({

    el: '#brands'
});