<template>
    <el-form
        ref="form"
        :model="form"
        label-position="left"
        label-width="120px"
    >
        <el-form-item :label="$t('admin.common.parent')">
            <el-select
                v-model="form.status"
            >
                <el-option
                    v-for="status in transactionStatus"
                    :key="status.value"
                    :label="status.name"
                    :value="status.value">
                </el-option>
            </el-select>
        </el-form-item>
        <el-form-item :label="$t('admin.common.name')">
            <el-input
                v-model="form.message"
                maxlength="255"
                show-word-limit
            />
        </el-form-item>
        <div class="submit-area">
            <el-button
                type="success"
                @click="handleSubmit"
            >
                {{ $t('admin.common.save') }}
            </el-button>
            <el-button @click="handleCancel">
                {{ $t('admin.common.cancel') }}
            </el-button>
        </div>
    </el-form>
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
            transactionStatus: {
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
            }
        },
        mounted () {
            this.initForm(this.target);
        },
        watch: {
            target: function (data) {
                this.initForm(data);
            },
        },
        methods: {
            initForm(data) {
                if(this._.isEmpty(data)) {
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

                    const uri = (this.mode === 'edit')
                        ? this.route('admin.ajax_update_history_transfer', { history: this.form.id})
                        : this.route('admin.ajax_create_history_transfer');
                    const response = (this.mode === 'edit')
                        ? await this.Request.patch(uri, this.form)
                        : await this.Request.post(uri, this.form);
                    this.onSuccess(response.data, this.mode);
                    this.resetForm();
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
                this.resetForm();
                this.onCancel();
            },
            resetForm() {
                this.$refs['form'].resetFields();
            }
        }
    }
</script>
