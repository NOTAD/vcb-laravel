<template>
    <div>
        <el-form
            ref="bankForm"
            :model="form"
            label-position="left"
            label-width="220px"
        >
            <el-form-item label="Tên đăng nhập">
                <el-input
                    v-model="form.username"
                    maxlength="255"
                    show-word-limit
                ></el-input>
            </el-form-item>
            <el-form-item label="Mật khẩu">
                <el-input
                    maxlength="255"
                    show-word-
                    type="password"
                    v-model="form.password">
                </el-input>
            </el-form-item>
            <el-form-item label="Số tài khoản">
                <el-input
                    maxlength="255"
                    show-word-limit
                    v-model="form.account_number">
                </el-input>
            </el-form-item>
          <el-form-item label="STKNAME">
            <el-input
                maxlength="255"
                show-word-limit
                v-model="form.stkname">
            </el-input>
          </el-form-item>
            <el-form-item v-if="mode === 'edit'">
                <el-switch
                    active-text="Tạo mới IMEI"
                    v-model="form.auto_imei"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
                <el-switch
                    active-text="Tạo mới Token"
                    v-model="form.auto_token"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
            </el-form-item>
            <el-form-item :label="$t('admin.common.status')">
                <el-select v-model="form.status">
                    <el-option
                        v-for="type in bankStatus"
                        :key="type.value"
                        :label="type.name"
                        :value="type.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <div class="row text-center">
                <el-form-item>
                    <el-button
                        type="success"
                        @click="handleSubmit"
                    >
                        {{ $t('admin.common.save') }}
                    </el-button>
                    <el-button @click="handleCancel">
                        {{ $t('admin.common.cancel') }}
                    </el-button>
                </el-form-item>
            </div>

        </el-form>
    </div>

</template>
<script>

export default {
    props: {
        onSuccess: {
            type: Function,
            default() {
                return null;
            }
        },
        onCancel: {
            type: Function,
            default() {
                return null;
            }
        },
        target: {
            type: Object,
            default() {
                return {};
            }
        },
        user: {
            type: Object,
            default() {
                return {};
            }
        },
        bankStatus: {
            type: Array,
            default() {
                return [];
            }
        },
    },
    data() {
        return {
            form: {},
            mode: '',
            isSearching: false,
        }
    },
    created() {
        this.initForm(this.target);
    },
    watch: {
        target(updatedRecord) {
            this.initForm(updatedRecord);
        },
    },
    methods: {
        initForm(data) {
            if (this._.isEmpty(data)) {
                this.form = {};
                this.mode = 'create';
            } else {
                this.form = this._.clone(data);
                this.mode = 'edit';
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
                this.form.content = this.Editor.getEditorData('editor');
                this.form.content_en = this.Editor.getEditorData('editor_en');
                const uri = (this.mode === 'edit')
                    ? this.route('admin.ajax_update_bank', {bank: this.form.id})
                    : this.route('admin.ajax_create_bank');
                const response = (this.mode === 'edit')
                    ? await this.Request.patch(uri, this.form)
                    : await this.Request.post(uri, this.form);
                this.onSuccess(response.data, this.mode);
                this.$refs['bankForm'].resetFields();
                this.$notify({
                    type: 'success',
                    title: this.$t('admin.common.success'),
                    message: (this.mode === 'edit') ? this.$t('admin.common.updated') : this.$t('admin.common.created'),
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
        },
        handleCancel() {
            this.$refs['bankForm'].resetFields();
            this.onCancel();
        },
    }
}
</script>
