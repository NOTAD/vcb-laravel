<template>
    <el-container class="manager-screen">
        <el-header>
            <div class="left">
                <el-button
                        type="success"
                        icon="el-icon-edit-outline"
                        @click="showForm()"
                >
                    {{ this.$t('admin.common.create')}}
                </el-button>
            </div>
        </el-header>
        <el-main>
            <el-card class="box-card">
                <el-table
                    :data="rolesDataSet"
                    style="width: 100%">
                    <el-table-column
                            prop="id"
                            label="ID"
                            sortable
                    />
                    <el-table-column
                            prop="name"
                            :label="$t('admin.common.name')"
                            sortable>
                    </el-table-column>
                    <el-table-column
                        prop="guard"
                        label="Guard"
                        sortable/>
                    <el-table-column
                        prop="level"
                        label="Level"
                        sortable/>
                    <el-table-column
                        label="Số quyền"
                    >
                        <template slot-scope="scope"> {{ scope.row.permissions.length }}</template>
                    </el-table-column>
                    <el-table-column
                            fixed="right"
                            :label="$t('admin.common.action')"
                    >
                        <template slot-scope="scope">
                            <el-button
                                plain
                                type="primary"
                                :title="$t('admin.common.edit')"
                                size="mini"
                                icon="el-icon-edit"
                                class="action-button"
                                @click="showForm(scope.row)"
                            >
                                {{ $t('admin.common.edit') }}
                            </el-button>
                            <el-button
                                plain
                                type="danger"
                                :title="$t('admin.common.delete')"
                                size="mini"
                                icon="el-icon-delete"
                                class="action-button"
                                @click="confirmDelete(scope.row)"
                            >
                                {{ $t('admin.common.delete') }}
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
            <el-dialog
                :title="formTitle"
                :visible.sync="isShowForm"
                class="form-dialog"
            >
                <role-form
                    :target="target"
                    :permissions="permissions"
                    :on-success="onSuccess"
                    :on-cancel="hideForm"
                />
            </el-dialog>
            <delete-confirm-dialog
                    :title="this.$t('admin.common.confirm_delete')"
                    :visible="isShowConfirmDelete"
                    :on-confirm="handleDelete"
                    :on-cancel="closeDeleteDialog"
            />
        </el-main>
    </el-container>
</template>
<script>
    import RoleForm from "./RoleForm";
    import DeleteConfirmDialog from './DeleteConfirmDialog';

    export default {
        components: { RoleForm, DeleteConfirmDialog },
        props: {
            roles: {
                type: Array,
                default() {
                    return [];
                },
            },
            permissions: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
        data(){
            return {
                rolesDataSet: this._.clone(this.roles),
                formTitle: '',
                isShowForm: false,
                isShowConfirmDelete: false,
                target: {},
            }
        },
        methods: {
            confirmDelete (target) {
                this.target = target
                this.isShowConfirmDelete = true
            },
            closeDeleteDialog() {
                this.isShowConfirmDelete = false;
            },
            onSuccess(data, mode) {
                this.isShowForm = false;
                if (this._.isEmpty(data)) {
                    return;
                }
                if (mode === 'create') {
                    this.rolesDataSet.push(data);
                } else {
                    this.rolesDataSet =  this._.map(this.rolesDataSet, (record) => {
                        return (record.id === data.id) ? data : record;
                    });
                }
                this.hideForm();
            },
            handlePaginate(page) {
                this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                let params = {};
                if (!this._.isEmpty(this.search)) {
                    params.search = this.search;
                }
                if (page > 1) {
                    params.page = page;
                }
                window.location = this.route('admin.blog_categories_index', params);
            },
            async handleDelete() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_delete_role', { role: this.target.id});
                    await this.Request.delete(uri);
                    this.rolesDataSet.splice(this._.findIndex(this.rolesDataSet, this.target), 1);
                    this.isShowConfirmDelete = false;
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: this.$t('admin.common.deleted'),
                    });
                } catch (e) {
                    const errorMessages = this.Request.errors(e.response);
                    this.$notify({
                        type: 'error',
                        title: this.$t('admin.common.error'),
                        message: this._.isEmpty(errorMessages) ? this.$t('admin.common.unknown_error') : errorMessages[0],
                    });
                }
                loading.close();
            },
            hideForm() {
                this.isShowForm = false;
            },
            showForm (target) {
                this.formTitle = this._.isEmpty(target)
                    ? this.$t('admin.common.create')
                    : this.$t('admin.common.edit') + ' #' + target.id;
                this.target = target;
                this.isShowForm = true
            },
        }
    }
</script>
