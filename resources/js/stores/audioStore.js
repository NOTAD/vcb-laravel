export const audioStore = {
    state: {
        audio: {
            src: '',
            productId: '',
        },
    },
    getters: {
        getAudio: state => state.audio
    },
    mutations: {
        setAudio (state, audio) {
            state.audio = audio;
        }
    }
};
