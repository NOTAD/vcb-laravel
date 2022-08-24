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
                <el-button
                    type="primary"
                    icon="el-icon-download"
                    class="action-button"
                    @click="handleExport"
                >
                    {{ $t('admin.common.export') }}
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
                    :data="usersDataSet"
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
                    <el-table-column
                        prop="cash"
                        :label="$t('admin.common.cash')"
                        sortable>
                        <template slot-scope="scope">
                            {{ Helper.formatMoney(scope.row.cash)}} VNĐ
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('admin.common.player')">
                        <template slot-scope="scope">
                            <el-switch
                                v-model="scope.row.is_player"
                                disabled
                            >
                            </el-switch>
                        </template>
                    </el-table-column>
                    <el-table-column
                    fixed="right"
                    :label="$t('admin.common.action')">
                    <template slot-scope="scope">
                        <el-button
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
                        v-if="users.total > users.per_page"
                        background
                        layout="prev, pager, next"
                        :total="users.total"
                        :current-page="users.current_page"
                        :page-size="users.per_page"
                        @current-change="handlePaginate"
                    />
                </div>
            </el-card>
            <el-dialog
                :title="formTitle"
                :visible.sync="isShowForm"
                width="50%"
            >
                <user-form
                    :target="target"
                    :on-success="onSuccess"
                    :on-cancel="hideForm"
                ></user-form>
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
    import UserForm from './UserForm';

    export default {
        components: { UserForm, DeleteConfirmDialog },
        props: {
            users: {
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
        },
        created() {
            this.initData();
        },
        data() {
            return {
                search: '',
                usersDataSet: this._.clone(this.users.data),
                isShowConfirmDelete: false,
                isShowForm: false,
                target: {},
                formTitle: '',
            }
        },
        methods: {
            initData(){
                this.usersDataSet = this._.map(this.usersDataSet, (record) => {
                     record.is_player = record.is_player.toString() === '1' || record.is_player;
                     return record;
                });
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
                window.location = this.route('admin.users_index', params);
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
                    this.usersDataSet.push(user);
                } else {
                    this.usersDataSet =  this._.map(this.usersDataSet, (record) => {
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
            handleExport() {
                window.open(this.route('admin.users_export'), '_blank');
            },
            async handleDelete() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_delete_user', { user: this.target.id });
                    await this.Request.delete(uri);
                    this.usersDataSet.splice(this._.findIndex(this.usersDataSet, this.target), 1);
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
