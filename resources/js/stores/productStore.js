export const productStore = {
    state: {
        product: {},
    },
    getters: {
        getProduct: state => state.product
    },
    mutations: {
        setProduct (state, product) {
            state.product = product;
        }
    }
};
