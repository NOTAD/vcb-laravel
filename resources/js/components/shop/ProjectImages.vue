<template>
    <div class="row">
        <div class="col-6 col-lg-4 col-xl-3" v-for="(item,index) in slider" :key="index">
            <div class="item">
                <a data-fancybox="gallery" :href="item.image"><img :src="item.image"></a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProjectImages",
    props: {
        slider: {
            Type: Object,
            default() {
                return {};
            },
        },
    },
    data: function () {
        return {
            images: [],
            index: null
        };
    },
    created() {
        this.initGagely();
    },
    methods: {
        initGagely() {
            for (let image in this.slider) {
                this.images.push(this.slider[image].image);
            }
        },
    }
}
</script>

<style scoped>
.image {
    float: left;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border: 1px solid #ebebeb;
    margin: 5px;
}
</style>
