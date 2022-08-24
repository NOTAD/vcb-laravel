import Vue from 'vue'
import Vuex from 'vuex'
import sharedMutations from 'vuex-shared-mutations'
import { cartStore } from './cartStore'
import { userStore } from './userStore';
import { productStore } from './productStore';
import { audioStore } from './audioStore';

Vue.use(Vuex);

export const shopStore = new Vuex.Store({
    modules: {
        cartStore,
        userStore,
        productStore,
        audioStore,
    },
    plugins: [sharedMutations({
        predicate: [
            'setCartProducts',
            'setUser',
            'setProduct',
            'setAudio',
        ]
    })],
});
