/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import Vuex from 'vuex';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import route from 'ziggy';
import { Ziggy } from './ziggy';
import { adminStore } from './stores/adminStore';
import { Editor } from "./common/editor";
import { Helper } from "./common/helper";
import { Request } from "./common/request";
import { env } from "./config/app";

import en from './en.json';
import vi from './vi.json';

import VueI18n from 'vue-i18n';


Vue.use(Vuex);
Vue.use(VueLodash, { lodash: lodash });
Vue.use(VueI18n);
Vue.use(ElementUI, { locale });

Vue.mixin({
    methods: {
        route: (name, params, absolute, config = Ziggy) => route(name, params, absolute, config),
    },
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const adminComponents = require.context('./components/admin', true, /\.vue$/i); // eslint-disable-line no-undef
// const chartComponents = require.context('./components/charts', true, /\.vue$/i); // eslint-disable-line no-undef
adminComponents.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], adminComponents(key).default));
// chartComponents.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], chartComponents(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const messages  = {
    en,
    vi,
}

const i18n = new VueI18n({
    locale: document.head.querySelector('meta[name="locale"]').content,
    messages: messages
});

Vue.prototype.Editor  = Editor;
Vue.prototype.Helper  = Helper;
Vue.prototype.Request  = Request;
Vue.prototype.env  = env;

window._dzusAdmin = new Vue({
    store: adminStore,
    el: '#app',
    i18n,
});


document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        document.getElementById('preloader').style.visibility = 'hidden';
    }, 100);
});

