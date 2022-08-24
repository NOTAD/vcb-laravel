import Vue from 'vue';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import { Helper } from "./common/helper";
import { Request } from "./common/request";
import route from 'ziggy';
import { Ziggy } from './ziggy';

import en from './en.json';
import vi from './vi.json';

import VueI18n from 'vue-i18n';

Vue.use(ElementUI, { locale });
Vue.use(VueLodash, { lodash: lodash });
Vue.use(VueI18n);
Vue.prototype.Helper  = Helper;
Vue.prototype.Request  = Request;

Vue.mixin({
    methods: {
        route: (name, params, absolute, config = Ziggy) => route(name, params, absolute, config),
    },
});

const files = require.context('./components/auth', true, /\.vue$/i); // eslint-disable-line no-undef
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const messages  = {
    en,
    vi,
}


const i18n = new VueI18n({
    locale: document.head.querySelector('meta[name="locale"]').content,
    messages: messages
});

window._dzusAuth = new Vue({
    el: '#app',
    i18n,
});

