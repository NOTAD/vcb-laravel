<template>
    <el-container class="file-manager">
        <el-header v-if="!isShowCrop">
            <div class="row">
                <div class="col-sm-8 text-left">
                    <el-upload
                            class="btn-upload"
                            ref="upload"
                            name="upload"
                            :multiple="true"
                            :http-request="handleUpload"
                            :show-file-list="false"
                            :action="this.route('admin.ajax_upload_file')"
                    >
                        <el-button
                                :title="$t('admin.common.upload')"
                                type="success"
                                icon="el-icon-upload"
                        >
                            {{ $t('admin.common.upload') }}
                        </el-button>
                    </el-upload>
                    <el-button
                        title="Tạo thư mục"
                        plain
                        type="primary"
                        icon="el-icon-folder-add"
                        @click="isShowMakeDirectory = true"
                    >
                        Tạo thư mục
                    </el-button>
                    <el-button
                            :title="$t('admin.common.delete')"
                            plain
                            type="danger"
                            icon="el-icon-delete"
                            @click="beforeExec('delete')"
                    >
                        {{ $t('admin.common.delete') }}
                    </el-button>
                </div>
                <div class="col-sm-4 text-right">
                    <el-button
                        title="Chọn ảnh"
                        size="mini"
                        v-if="currentSelected > 0"
                        @click="handleSendImageListToSelector"
                    >
                        <i class="material-icons">done_all</i>
                        {{ currentSelected }}
                    </el-button>
                    <el-dropdown
                        class="float-right"
                        @command="handleSort"
                    >
                        <el-button title="Sắp xếp" size="mini">
                            <i class="material-icons">swap_vert</i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="1">Mới nhất</el-dropdown-item>
                            <el-dropdown-item command="2">Cũ nhất</el-dropdown-item>
                            <el-dropdown-item command="3">Kích thước giảm dần</el-dropdown-item>
                            <el-dropdown-item command="4" >Kích thước tăng dần</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </div>
        </el-header>
        <el-main>
                <image-crop
                        v-if="targetImage && isShowCrop"
                        :target-image="targetImage"
                        :directory="directory"
                        :on-crop-success="onCropSuccess"
                        :on-crop-cancel="onCropCancel"
                ></image-crop>
                <div v-if="!isShowCrop" class="breadcumb col-sm-12 no-mr-left no-pd-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li v-for="item in breadCrumb"
                                :key="item.path"
                                class="breadcrumb-item">
                                <a href="#" @click="handleBrowser(item.path)"> {{ item.name }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div v-if="!isShowCrop">
                    <div class="col-sm-12 no-mr-left no-pd-left">
                        <el-card
                            class="box-card image-item"
                            v-for="directory in directories" :key="directory.name"
                        >
                            <el-image
                                class="img-rounded"
                                src="/images/admin/folder.png"
                                @click="handleBrowser(directory.path)"
                            ></el-image>
                            <el-tag class="folder-name">{{ directory.name }}</el-tag>
                            <div class="select-area">
                                <el-button
                                    :type="directory.is_selected ? 'success' : '' "
                                    icon="el-icon-check"
                                    circle
                                    size="mini"
                                    title="Chọn nhiều"
                                    @click="handleTick(directory)"
                                ></el-button>
                            </div>
                        </el-card>
                    </div>
                    <div class="col-sm-12 no-mr-left no-pd-left">
                        <el-card
                            class="box-card image-item"
                            v-for="file in filesSource" :key="file.name"
                        >
                            <el-image
                                class="img-rounded"
                                title="Chọn để xem ảnh"
                                :src="file.url"
                                :preview-src-list="previewSource"
                                @click="handleChooseImage(file)"
                            >
                                <div slot="placeholder">
                                    <i class="el-icon-loading"></i>
                                    Đang tải...
                                </div>
                                <div slot="error">
                                    <i class="el-icon-picture-outline"></i>
                                    Ảnh lỗi
                                </div>
                            </el-image>
                            <el-tag icon="el-icon-picture" class="size-item">{{ file.size }} KB</el-tag>
                            <el-tag icon="el-icon-time" class="time-item">{{ file.last_modified }}</el-tag>
                            <el-button-group class="image-button">
                                <el-button
                                    title="Chỉnh sửa"
                                    type="warning"
                                    icon="el-icon-edit"
                                    size="small"
                                    @click="showCropArea(file)"
                                ></el-button>
                                <a
                                    :href="file.url"
                                    download
                                >
                                    <el-button
                                        title="Tải xuống"
                                        type="success"
                                        icon="el-icon-download"
                                        size="small"
                                    ></el-button>
                                </a>
                            </el-button-group>
                            <div class="select-area">
                                <el-button
                                    :type="file.is_selected ? 'success' : '' "
                                    icon="el-icon-check"
                                    circle
                                    size="mini"
                                    title="Chọn nhiều ảnh"
                                    @click="handleTick(file)"
                                ></el-button>
                            </div>
                        </el-card>
                    </div>
                </div>
                <delete-confirm-dialog
                        :title="this.$t('admin.common.confirm_delete')"
                        :visible="isShowConfirmDelete"
                        :on-confirm="handleDelete"
                        :on-cancel="closeDeleteDialog"
                />
                <el-dialog
                        title="Đổi kích thước"
                        :visible.sync="isShowResize"
                        class="file-dialog"
                        center
                >
                    <div class="text-center">
                        <el-form
                                :inline="true"
                                :model="resize"
                        >
                            <el-form-item label="Width">
                                <el-input
                                        v-model="resize.width"
                                        class="resize-input"
                                ></el-input>
                            </el-form-item>
                            <el-form-item label="Height">
                                <el-input
                                        v-model="resize.height"
                                        class="resize-input"
                                ></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <span slot="footer" class="dialog-footer">
                        <el-button type="primary" @click="handleResize">Xác nhận</el-button>
                        <el-button @click="isShowResize = false">Hủy</el-button>
                </span>
                </el-dialog>
                <el-dialog
                    title="Tạo thư mục"
                    :visible.sync="isShowMakeDirectory"
                    class="file-dialog"
                    center
                >
                    <div class="text-center">
                    <el-input v-model="directoryName"></el-input>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="handleMakeDirectory">Tạo</el-button>
                    <el-button @click="isShowMakeDirectory = false">Hủy</el-button>
                </span>
            </el-dialog>
        </el-main>
    </el-container>

</template>
<script>
    import ImageCrop from "./ImageCrop";
    import { mapMutations } from 'vuex';
    import DeleteConfirmDialog from './DeleteConfirmDialog';

    export default {
        components: { ImageCrop, DeleteConfirmDialog },
        props: {
            hasEditor: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            hasSelector: {
                type: Boolean,
                default() {
                    return true;
                }
            }
        },
        data() {
            return {
                filesSource: [],
                directories: [],
                directory: '',
                breadCrumb: [],
                previewSource: [],
                targetSource: [],
                isShowConfirmDelete: false,
                isShowCrop: false,
                targetImage: null,
                isShowResize: false,
                isShowMakeDirectory: false,
                resize: { width: 250, height: 250 },
                currentSelected: 0,
                directoryName: '',
                onlyDisplaySelected: false,
            };
        },
        created() {
          if (this.hasEditor || this.hasSelector) {
              this.listView = false;
          }
        },
        mounted() {
          this.handleBrowser();
        },
        methods: {
            ...mapMutations([
                'setImage',
                'setImageList'
            ]),
            handleChooseImage(image) {
                if(this.hasEditor) {
                    this.handleSendToEditor(image);
                } else if(this.hasSelector) {
                    this.handleSendToSelector(image);
                } else {
                    this.setImagePreview(image);
                }
            },
            buildbreadCrumb() {
                let root = this.directory.split('/');
                root.splice(4, root.length - 4);

                this.breadCrumb = [
                    {
                        name: 'Root',
                        path: root.join('/'),
                    },
                ];

                let separates = this.directory.split('/');
                separates.splice(0, 4);

                const links = this._.map(separates, (item, key) => {
                    let temp = this.directory.split('/');
                    temp.splice(key + 5, temp.length - key + 1);
                    return {
                        name: item,
                        path: temp.join('/')
                    };
                });

                this.breadCrumb = this.breadCrumb.concat(links);
            },
            async handleBack() {
                let separates = this.directory.split('/');
                if(separates.length > 4) {
                    separates.splice(separates.length - 1);
                    const directory =  separates.join('/');
                    this.handleBrowser(directory);
                }
            },
            handleSendToSelector(file) {
                this.setImage(file.url);
                window.close();
            },
            handleSendImageListToSelector() {
                let images = [];
                this._.forEach(this.filesSource, file => {
                    if(file.is_selected) {
                        images.push(file.url);
                    }
                });
                this.setImageList(images);
                window.close();
            },
            isSelected(file) {
                return !this._.isEmpty(file.is_selected) ? 'success' : ''
            },
            handleTick(targetFile) {
                let before = false;

                if(targetFile.is_selected === true) {
                    before = true;
                }

                targetFile.is_selected = !targetFile.is_selected;

                if(before) {
                    this.currentSelected--;
                } else {
                    this.currentSelected++;
                }
            },
            onCropSuccess(file) {
               this.filesSource.push(file);
               this.handleSort(1);
               this.isShowCrop = false;
            },
            onCropCancel() {
                this.isShowCrop = false;
            },
            handleSort(command) {
                  switch (parseInt(command)) {
                      case 1:
                          this.sortFiles('last_modified', 'desc');
                          break;
                      case 2:
                          this.sortFiles('last_modified', 'asc');
                          break;
                      case 3:
                          this.sortFiles('size', 'desc');
                          break;
                      case 4:
                          this.sortFiles('size', 'asc');
                          break;
                      default:
                          this.sortFiles('last_modified', 'asc');
                          break;
                  }
            },
            sortFiles(field, type) {
              this.filesSource = this._.orderBy(this.filesSource, [field], [type]);
            },
            handleSendToEditor(file) {
                try {
                    this.Editor.returnFileUrl(file.url);
                } catch (e) {
                    this.$notify({
                        type: 'error',
                        title: 'Xảy ra lỗi',
                        message: 'Không tìm thấy trình soạn thảo',
                    });
                }
            },
            showCropArea(file){
                this.targetImage = file;
                this.isShowCrop = true;
            },
            setImagePreview(file){
                this.previewSource = [file.url];
                let source = this._.map(this.filesSource, (file) => {
                    return file.url;
                });
                this._.remove(source, url => url === file.url);
                this.previewSource = this.previewSource.concat(source);
            },
            beforeExec(action) {
                if(this.currentSelected > 0) {
                    switch(action) {
                        case 'delete':
                            this.isShowConfirmDelete = true;
                            break;
                        case 'resize':
                            if(this.currentSelected > 10) {
                                this.$notify({
                                    type: 'warning',
                                    title: 'Chú ý',
                                    message: 'Chỉ được phép đổi kích thước tối đa 10 ảnh cùng lúc.',
                                });
                            } else {
                                this.isShowResize = true;
                            }
                            break;
                    }
                } else {
                    this.$notify({
                        type: 'warning',
                        title: 'Chú ý',
                        message: 'Bạn chưa chọn ảnh nào.',
                    });
                }
            },
            closeDeleteDialog() {
                this.isShowConfirmDelete = false;
            },
            async handleUpload(data) {
                this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    let formData = new FormData();
                    formData.append('upload', data.file);
                    formData.append('directory', this.directory);
                    const uri = this.route('admin.ajax_upload_file');
                    const response = await this.Request.upload(uri, formData);
                    this.$notify({
                        type: 'success',
                        title: 'Thành công',
                        message: 'Tải ảnh thành công.',
                    });
                    this.filesSource.push(response.data);
                    this.handleSort(1);
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: 'Lỗi',
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                this.$loading().close();
            },
            async handleBrowser(path) {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const directory = this._.isEmpty(path) ? this.directory : path;
                    const uri = this.route('admin.ajax_browser_files');
                    const response = await this.Request.post(uri, {
                        directory,
                    });
                    this.filesSource = response.data.files;
                    this.directories = response.data.directories;
                    this.directory = response.data.directory;
                    this.buildbreadCrumb();
                    this.sortFiles('last_modified', 'desc');
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: this.$t('admin.common.error'),
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            },
            async handleDelete() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    let files = this._.clone(this.filesSource);
                    files = this._.remove(files, (item) => {
                        return item.is_selected === true;
                    });

                    let directories = this._.clone(this.directories);
                    directories = this._.remove(directories, (item) => {
                        return item.is_selected === true;
                    });

                    const uri = this.route('admin.ajax_delete_files');
                    await this.Request.patch(uri, {
                        files,
                        directories
                    });

                   this._.remove(this.filesSource, (item) => {
                        return item.is_selected === true;
                    });

                   this._.remove(this.directories, (item) => {
                        return item.is_selected === true;
                    });

                    this.isShowConfirmDelete = false;
                    this.$notify({
                        type: 'success',
                        title: 'Thành công',
                        message: 'Đã xóa',
                    });
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: 'Lỗi',
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            },
            async handleResize() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    let files = this._.clone(this.filesSource);
                    files = this._.remove(files, (item) => {
                        return item.is_selected === true;
                    });
                    const uri = this.route('admin.ajax_resize_files');
                    const response = await this.Request.patch(uri, {
                        files,
                        width: this.resize.width,
                        height: this.resize.height,
                    });
                    this.filesSource = this.filesSource.concat(response.data);
                    this.handleSort(1);
                    this.isShowResize = false;
                    this.$notify({
                        type: 'success',
                        title: 'Thành công',
                        message: 'Ảnh đã đổi kích thước',
                    });
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: 'Lỗi',
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            },
            async handleMakeDirectory() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {

                    const uri = this.route('admin.ajax_make_directory');
                    const response = await this.Request.post(uri, {
                        directory: this.directory,
                        name: this.directoryName,
                    });
                    this.directories.push(response.data);
                    this.isShowMakeDirectory = false;
                    this.directoryName = '';
                    this.$notify({
                        type: 'success',
                        title: 'Thành công',
                        message: 'Đã tạo thư mục',
                    });
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    console.log(e); //eslint-disable-line
                    this.$notify({
                        type: 'error',
                        title: 'Lỗi',
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            },
        }
    }
</script>
