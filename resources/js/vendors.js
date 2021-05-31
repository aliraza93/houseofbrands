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

//Vendors Components
Vue.component('vendors-list', require('./components/vendors/index.vue').default)
Vue.component('add-vendor', require('./components/vendors/AddVendor.vue').default)

Vue.component('vendor-products-list', require('./components/vendors/products/index.vue').default)
Vue.component('add-vendor-product', require('./components/vendors/products/AddProduct.vue').default)

var app = new Vue({

    el: '#vendors'
});