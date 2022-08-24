<template>
    <div>
        <div class="col-sm-12 card-crop">
            <vue-cropper
                ref="cropper"
                class="crop-area"
                :aspectRatio="1"
                :src="imageSrc"
                preview=".preview"
            />
            <div class="preview cropper-bg"></div>
            <div class="info-area">
                Mime: {{ targetImage.mime }} <br/>
                Size: {{ targetImage.size }} KB <br/>
                Last modified: <br/> {{ targetImage.last_modified }}
            </div>
        </div>
        <div class="col-sm-12 flex-column">
            <el-button-group>
                <el-button
                        title="Rotate"
                        type="warning"
                        size="mini"
                        @click="rotate(90)"
                >
                    <i class="material-icons">screen_rotation</i>
                </el-button>
                <el-button
                        title="FlipX"
                        type="warning"
                        size="mini"
                        @click="flipX"
                >
                    <i class="material-icons">swap_horiz</i>
                </el-button>
                <el-button
                        title="FlipY"
                        type="warning"
                        size="mini"
                        @click="flipY"
                >
                    <i class="material-icons">swap_vert</i>
                </el-button>
            </el-button-group>

            <el-button-group>
                <el-button
                        title="Scale 16:9"
                        type="primary"
                        size="mini"
                        @click="setAspectRatio(16/9)"
                >
                    <i class="material-icons">crop_16_9</i>
                </el-button>
                <el-button
                        title="Scale 4:3"
                        type="primary"
                        size="mini"
                        @click="setAspectRatio(4/3)"
                >
                    <i class="material-icons">crop_3_2</i>
                </el-button>
                <el-button
                        title="Scale 2:3"
                        type="primary"
                        size="mini"
                        @click="setAspectRatio(2/3)"
                >
                    <i class="material-icons">crop_portrait</i>
                </el-button>
                <el-button
                        title="Scale 1:1"
                        type="primary"
                        size="mini"
                        @click="setAspectRatio(1)"
                >
                    <i class="material-icons">crop_square</i>
                </el-button>
                <el-button
                        title="Free Scale"
                        type="primary"
                        size="mini"
                        @click="setAspectRatio(null)"
                >
                    <i class="material-icons">crop_free</i>
                </el-button>
            </el-button-group>

            <el-button-group>
                <el-button
                        title="Reset"
                        type="success"
                        size="mini"
                        @click="reset"
                >
                    <i class="material-icons">refresh</i>
                </el-button>
                <el-button
                        title="Save"
                        type="success"
                        size="mini"
                        @click="cropImage()"
                >
                    <i class="material-icons">save</i>
                </el-button>
                <el-button
                        title="Cancel"
                        type="danger"
                        size="mini"
                        @click="onCropCancel()"
                >
                    <i class="material-icons">close</i>
                </el-button>
            </el-button-group>

        </div>
    </div>
</template>

<script>
    import VueCropper from 'vue-cropperjs';
    import 'cropperjs/dist/cropper.css';

    export default {
        components: {
            VueCropper,
        },
        props: {
            targetImage: {
                type: Object,
                default() {
                    return {};
                }
            },
            directory: {
                type: String,
                default: '',
            },
            onCropSuccess: {
                type: Function,
                default() {
                    return null;
                }
            },
            onCropCancel: {
                type: Function,
                default() {
                    return null;
                }
            }
        },
        data() {
            return {
                scaleX: 1,
                scaleY: 1,
                img: 'images/bg.jpg',
            };
        },
        computed: {
            imageSrc() {
                return this._.isEmpty(this.targetImage.url) ? this.img : this.targetImage.url;
            }
        },
        watch: {
            imageSrc() {
                this.replaceImg(this.targetImage.url);
            }

        },
        methods: {
            replaceImg(url) {
                this.$refs.cropper.replace(url);
            },
            setAspectRatio(ratio) {
                this.$refs.cropper.setAspectRatio(ratio);
            },
            async callbackUpload(blob) {
                this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_upload_file');
                    const formData = new FormData();
                    formData.append('upload', blob);
                    formData.append('directory', this.directory);
                    const response = await this.Request.upload(uri, formData);
                    this.onCropSuccess(response.data);
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: 'File cropped',
                    });
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: this.$t('admin.common.error'),
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                this.$loading().close();
            },
            cropImage() {
                this.$refs.cropper.getCroppedCanvas().toBlob(this.callbackUpload);
            },
            flipX() {
                this.scaleX = (this.scaleX === -1) ? 1 : -1;
                this.$refs.cropper.scaleX(this.scaleX);
            },
            flipY() {
                this.scaleY = (this.scaleY === -1) ? 1 : -1;
                this.$refs.cropper.scaleY(this.scaleY);
            },
            reset() {
                this.$refs.cropper.reset();
            },
            rotate(deg) {
                this.$refs.cropper.rotate(deg);
            },

        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        width: 1024px;
        margin: 0 auto;
    }

    input[type="file"] {
        display: none;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0 5px 0;
    }

    .header h2 {
        margin: 0;
    }

    .header a {
        text-decoration: none;
        color: black;
    }

    .content {
        display: flex;
        justify-content: space-between;
    }

    .cropper-area {
        width: 614px;
    }

    .actions {
        margin-top: 1rem;
    }

    .actions a {
        display: inline-block;
        padding: 5px 15px;
        background: #0062CC;
        color: white;
        text-decoration: none;
        border-radius: 3px;
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    textarea {
        width: 100%;
        height: 100px;
    }

    .preview-area {
        width: 307px;
    }

    .preview-area p {
        font-size: 1.25rem;
        margin: 0;
        margin-bottom: 1rem;
    }

    .preview-area p:last-of-type {
        margin-top: 1rem;
    }

    .preview {
        width: 100%;
        height: calc(372px * (9 / 16));
        overflow: hidden;
    }

    .crop-placeholder {
        width: 100%;
        height: 200px;
        background: #ccc;
    }

    .cropped-image img {
        max-width: 100%;
    }
</style>
