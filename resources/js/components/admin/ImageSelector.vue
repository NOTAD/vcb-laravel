<template>
    <el-image
        class="img-rounded image-select"
        :title="$t('admin.common.select_image')"
        :src="src"
        @click="handleSelectImage"
    >
        <div slot="placeholder"><i class="el-icon-loading"></i></div>
        <div slot="error"><img  @click="handleSelectImage" src="/images/admin/no-img.jpg" alt="" /></div>
    </el-image>
</template>
<script>
    import { mapState, mapMutations } from 'vuex';

    export default {
        props: {
            selectorId: {
                Type: String,
                default: '',
            },
            source: {
                Type: String,
                default: '/images/admin/no-img.jpg',
            },
            onSelect: {
                Type: Function,
                default() {
                    return null;
                },
            },
        },
        data() {
            return {
                src: this._.clone(this.source),
                currentSelector: '',
            };
        },
        computed: {
            ...mapState({
                image: state => state.imageStore.image,
                selector: state => state.imageStore.selector,
            }),
        },
        watch: {
            image(data) {
                if(this.currentSelector === this.selectorId) {
                    this.src = data;
                    this.onSelect(data);
                }
            },
            selector(data) {
                this.currentSelector = data;
            },
            source(data) {
                this.src = data;
            }
        },
        methods: {
            ...mapMutations([
                'setImage',
                'setSelector'
            ]),
            handleSelectImage() {
                this.setSelector(this.selectorId);
                this.Editor.browserImage();
            },
        },
    }
</script>
