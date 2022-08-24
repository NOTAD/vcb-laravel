<template>
    <el-container class="manager-screen">
        <el-main>
            <el-form
                ref="form"
                :model="form"
                label-position="left"
                label-width="175px"
                class="mr-clear form-dialog"
            >
                <el-tabs type="border-card">
                    <el-tab-pane label="Thông tin chung">
                        <el-form-item :label="$t('admin.common.site_name')">
                            <el-input
                                    v-model="form.name"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.address')">
                            <el-input
                                    v-model="form.address"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.phone')">
                            <el-input
                                    v-model="form.phone"
                                    maxlength="15"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.hotline')">
                            <el-input
                                    v-model="form.hotline"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.email')">
                            <el-input
                                    v-model="form.email"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>

                        <el-form-item :label="$t('admin.common.color')">
                            <el-color-picker v-model="form.color" show-alpha></el-color-picker>
                        </el-form-item>

                        <el-form-item label="Logo">
                            <el-image
                                class="img-rounded image-select"
                                title="Nhấp để chọn ảnh"
                                :src="form.logo"
                                @click="handleSelectImage('logo')"
                            >
                                <div slot="placeholder"><i class="el-icon-loading"></i></div>
                                <div slot="error"><img  @click="handleSelectImage('logo')" src="/images/admin/no-img.jpg" alt="" /></div>
                            </el-image>
                        </el-form-item>

                        <el-form-item label="Favicon">
                            <el-image
                                class="img-rounded image-select"
                                title="Nhấp để chọn ảnh"
                                :src="form.favicon"
                                @click="handleSelectImage('favicon')"
                            >
                                <div slot="placeholder"><i class="el-icon-loading"></i></div>
                                <div slot="error"><img  @click="handleSelectImage('favicon')" src="/images/admin/no-img.jpg" alt="" /></div>
                            </el-image>
                        </el-form-item>

                        <el-form-item :label="$t('admin.common.title')">
                            <el-input
                                    v-model="form.title"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.keyword')">
                            <el-input
                                    v-model="form.keyword"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.description')">
                            <el-input
                                    v-model="form.description"
                                    maxlength="255"
                                    show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('admin.common.thumbnail')">
                            <el-image
                                    class="img-rounded image-select"
                                    title="Nhấp để chọn ảnh"
                                    :src="form.thumbnail"
                                    @click="handleSelectImage('thumbnail')"
                            >
                                <div slot="placeholder"><i class="el-icon-loading"></i></div>
                                <div slot="error"><img  @click="handleSelectImage('thumbnail')" src="/images/admin/no-img.jpg" alt="" /></div>
                            </el-image>
                        </el-form-item>

                        <el-form-item label="GG Analytic">
                            <el-input
                                v-model="form.gg_analytic_id"
                            ></el-input>
                        </el-form-item>


                        <el-form-item label="Facebook">
                            <el-input v-model="form.facebook_id"></el-input>
                        </el-form-item>

                        <el-form-item label="Youtube">
                            <el-input v-model="form.youtube"></el-input>
                        </el-form-item>
                        <el-form-item label="Instagram">
                            <el-input v-model="form.instagram"></el-input>
                        </el-form-item>

                    </el-tab-pane>
                    <el-tab-pane label="Slider">
                        <div class="row">
                            <div class="col-sm-12 mr-clear">
                                <div class="col-sm-12">
                                    <el-button
                                        plain
                                        type="success"
                                        @click="handleSelectImage('slider')"
                                    >Thêm ảnh</el-button>
                                </div>
                            </div>
                            <div class="col-sm-12 mr-clear">
                                    <div
                                        v-for="(slide, i) in slider"
                                        :key="i"
                                        class="image-item col-md-2 col-lg-3"
                                    >
                                        <el-image class="img-rounded" :src="slide.image">
                                            <div slot="placeholder"><i class="el-icon-loading"></i></div>
                                            <div slot="error"><img src="/images/admin/no-img.jpg" alt="" /></div>
                                        </el-image>
                                        <el-input v-model="slide.link"></el-input>
                                        <el-button
                                                class="image-button"
                                                title="Xóa ảnh"
                                                size="small"
                                                type="danger"
                                                plain
                                                icon="el-icon-circle-close"
                                                circle
                                                @click="handleRemoveSilde(slider, i)"
                                        />

                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </el-form>
            <div class="row text-center">
                <el-button
                        type="success"
                        @click="handleSubmit"
                >{{ $t('admin.common.save') }}</el-button>
            </div>
        </el-main>
    </el-container>
</template>
<script>
    import { mapState, mapMutations } from 'vuex';

    export default {
        props: {
            user: {
                Type: Object,
                default() {
                    return {};
                },
            },
            setting: {
                Type: Object,
                default() {
                    return {};
                },
            },
        },
        data() {
            return {
                form: this._.clone(this.setting),
                slider: [],
                imageTarget: '',
            };
        },
        created() {
            this.initImages();
        },
        watch: {
            image(image) {
                this.updateImage(image);
            },
            imageList(data) {
                this.updateImageList(data);
            },
        },
        computed: {
            ...mapState({
                image: state => state.imageStore.image,
                imageList: state => state.imageStore.imageList,
            }),
        },
        methods: {
            ...mapMutations([
                'setImage',
                'setImageList'
            ]),
            handleSelectImage(imageTarget) {
                this.imageTarget = imageTarget;
                this.Editor.browserImage();
            },
            handleRemoveSilde(slider, index) {
                slider.splice(index, 1);
            },
            updateImageList(list) {
                this._.forEach(list, image => {
                    this.slider.push({
                        image,
                        link: '',
                    });
                });
            },
            updateImage(image) {
                if(this.imageTarget === 'slider') {
                    this.slider.push({
                        image,
                        link: '',
                    });
                } else {
                    this.form[this.imageTarget] = image;
                }
                this.imageTarget = '';
                this.setImage('');
            },
            initImages() {
                this.slider = [];
                if(!this._.isEmpty(this.form.slider)) {
                    this.slider = this.Helper.convertJson(this.form.slider);
                }
                if(this._.isEmpty(this.form.logo)) {
                    this.form.logo = '/images/admin/no-img.jpg';
                }
                if(this._.isEmpty(this.form.thumbnail)) {
                    this.form.thumbnail = '/images/admin/no-img.jpg';
                }
            },
            async handleSubmit() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    this.form.slider = this.slider;
                    const uri = this.route('admin.ajax_update_setting', { setting: this.form.id});
                    await this.Request.patch(uri, this.form);
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: this.$t('admin.common.updated'),
                    });
                } catch (e) {
                    console.log(e); // eslint-disable-line
                    const errorMessages = this.Request.errors(e.response);
                    this.$notify({
                        type: 'error',
                        title: this.$t('admin.common.error'),
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            }
        },

    }
</script>
