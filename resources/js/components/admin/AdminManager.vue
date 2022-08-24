<template>
    <el-container class="manager-screen">
        <el-header>
            <div class="left">
                <el-button
                    type="success"
                    icon="el-icon-edit-outline"
                    class="action-button"
                    @click="showForm({})"
                >
                    {{ $t('admin.common.create') }}
                </el-button>
            </div>
            <div class="right">
                <el-input
                    class="search"
                    :placeholder="$t('admin.common.search')"
                    v-model="search"
                    @keyup.enter.native="handlePaginate(1)"
                />
            </div>
        </el-header>
        <el-main>
            <el-card class="box-card">
                <el-table
                    :data="adminsDataSet"
                    style="width: 100%">
                    <el-table-column
                            prop="id"
                            label="Mã tài khoản"
                            sortable
                    />
                    <el-table-column
                            prop="name"
                            :label="$t('admin.common.name')"
                            sortable>
                    </el-table-column>
                    <el-table-column
                            prop="email"
                            label="Email"
                            sortable>
                    </el-table-column>
                    <el-table-column
                            prop="phone"
                            :label="$t('admin.common.phone')"
                            sortable>
                    </el-table-column>
                    <el-table-column :label="$t('admin.common.role')">
                        <template slot-scope="scope">
                            <p
                                v-for="role in scope.row.roles"
                                :key="role.id"
                                class="text-left"
                            >
                                {{ role.name }}
                            </p>
                        </template>
                    </el-table-column>
                    <el-table-column
                            fixed="right"
                            :label="$t('admin.common.action')">
                        <template slot-scope="scope">
                            <el-button
                                    v-if="canEdit(scope.row)"
                                    plain
                                    type="primary"
                                    size="mini"
                                    icon="el-icon-edit"
                                    class="action-button"
                                    :title="$t('admin.common.edit')"
                                    @click="showForm(scope.row)"
                            >
                            </el-button>
                            <el-button
                                    v-if="canDelete(scope.row)"
                                    plain
                                    type="danger"
                                    size="mini"
                                    icon="el-icon-delete"
                                    class="action-button"
                                    :title="$t('admin.common.delete')"
                                    @click="confirmDelete(scope.row)"
                            >
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="pagination">
                    <el-pagination
                            v-if="admins.total > admins.per_page"
                            background
                            layout="prev, pager, next"
                            :total="admins.total"
                            :current-page="admins.current_page"
                            :page-size="admins.per_page"
                            @current-change="handlePaginate"
                    />
                </div>
            </el-card>
            <el-dialog
                :title="formTitle"
                :visible.sync="isShowForm"
                width="50%"
            >
                <admin-form
                    :target="target"
                    :admin-roles="adminRoles"
                    :on-success="onSuccess"
                    :on-cancel="hideForm"
                ></admin-form>
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
    import DeleteConfirmDialog from "./DeleteConfirmDialog";
    import AdminForm from './AdminForm';

    export default {
        components: { AdminForm, DeleteConfirmDialog },
        props: {
            admins: {
                type: Object,
                default() {
                    return {};
                }
            },
            adminRoles: {
                type: Array,
                default() {
                    return [];
                }
            },
            user: {
                type: Object,
                default() {
                    return {};
                }
            },
        },
        data() {
            return {
                search: '',
                adminsDataSet: this._.clone(this.admins.data),
                isShowConfirmDelete: false,
                isShowForm: false,
                target: {},
                formTitle: '',
                adminLevel: this.getRoleLevel(this.user.roles),
            }
        },
        methods: {
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
                window.location = this.route('admin.admins_index', params);
            },
            getRoleLevel(roles) {
                let level = null;

                this._.forEach(roles, role => {
                    if(level === null || level > role.level) {
                        level = role.level;
                    }
                });

               return level;
            },
            canEdit(record) {
                if(this.user.id === record.id) {
                    return true;
                }
                const level = this.getRoleLevel(record.roles);

                return level > this.adminLevel;
            },
            canDelete(record) {
                const level = this.getRoleLevel(record.roles);

                return level > this.adminLevel;
            },
            showForm(target) {
                this.target = target;
                this.formTitle = this._.isEmpty(target)
                    ? this.$t('admin.common.create')
                    : this.$t('admin.common.edit') + '#' + target.id;
                this.isShowForm = true;
            },
            onSuccess(user, mode) {
                this.hideForm();
                if (this._.isEmpty(user)) {
                    return;
                }
                if (mode === 'create') {
                    this.adminsDataSet.push(user);
                } else {
                    this.adminsDataSet =  this._.map(this.adminsDataSet, (record) => {
                        return (record.id === user.id) ? user : record;
                    });
                }
            },
            hideForm() {
                this.isShowForm = false;
            },
            confirmDelete(target) {
                this.isShowConfirmDelete = true;
                this.target = target;
            },
            closeDeleteDialog() {
                this.isShowConfirmDelete = false;
            },
            async handleDelete() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_delete_admin', { admin: this.target.id });
                    await this.Request.delete(uri);
                    this.adminsDataSet.splice(this._.findIndex(this.adminsDataSet, this.target), 1);
                    this.isShowConfirmDelete = false;
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: this.$t('admin.common.deleted'),
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
        }
    }
</script>
