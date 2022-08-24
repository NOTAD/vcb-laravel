export const cartStore = {
    state: {
        products: [],
    },
    getters: {
        getCartProducts: state => state.products
    },
    mutations: {
        setCartProducts (state, products) {
            state.products = products;
        }
    }
};
