/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import Vuex from 'vuex';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import route from 'ziggy';
import { Ziggy } from './ziggy';
import { shopStore } from './stores/shopStore';
import { Helper } from './common/helper';
import { Request } from './common/request';
import { env } from './config/app';

import en from './en.json';
import vi from './vi.json';

import VueI18n from 'vue-i18n';

Vue.use(Vuex);
Vue.use(VueLodash, { lodash: lodash });
Vue.use(VueI18n);


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

const files = require.context('./components/shop', true, /\.vue$/i); // eslint-disable-line no-undef
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

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

Vue.prototype.Helper  = Helper;
Vue.prototype.Request  = Request;
Vue.prototype.env  = env;
window._shop = new Vue({
    store: shopStore,
    el: '#app',
    i18n,
});

window.fbAsyncInit = function() {
    // eslint-disable-next-line no-undef
    FB.init({
        xfbml: true,
        version: 'v5.0'
    });
};
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
