<template>
    <el-container class="manager-screen">
        <el-header>
            <div class="left">
                <el-button
                    type="success"
                    icon="el-icon-edit-outline"
                    @click="showForm()"
                >
                    {{ this.$t('admin.common.create') }}
                </el-button>
            </div>
            <div class="right">
                <el-input
                    class="search"
                    :placeholder="this.$t('admin.common.search')"
                    v-model="search"
                    @keyup.enter.native="handlePaginate(1)"
                />
            </div>
        </el-header>
        <el-main>
            <el-card class="box-card">
                <el-table
                    :data="banksDataSet"
                    :default-sort="{prop: 'id', order: 'descending'}"
                    style="width: 100%">
                    <el-table-column
                        prop="id"
                        label="ID"
                        sortable
                    >
                    </el-table-column>
                    <el-table-column
                        prop="username"
                        label="Tên đăng nhập"
                        sortable>
                    </el-table-column>
                    <el-table-column
                        prop="account_number"
                        label="TÀI KHOẢN"
                        sortable>
                    </el-table-column>
                    <el-table-column
                        prop="imei"
                        label="IMEI"
                        sortable>
                    </el-table-column>
                    <el-table-column
                        prop="token"
                        label="Token"
                        sortable>
                    </el-table-column>
                  <el-table-column
                      prop="stkname"
                      label="STKNAME"
                      sortable>
                  </el-table-column>
                    <el-table-column
                        prop="status_name"
                        label="Trạng thái"
                        sortable>
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        :label="$t('admin.common.action')">
                        <template slot-scope="scope">
                            <el-button
                                plain
                                type="primary"
                                :title="$t('admin.common.edit')"
                                size="mini"
                                icon="el-icon-edit"
                                class="action-button"
                                @click="showForm(scope.row)">
                                {{ $t('admin.common.edit') }}
                            </el-button>
                            <el-button
                                plain
                                type="danger"
                                :title="$t('admin.common.delete')"
                                size="mini"
                                icon="el-icon-delete"
                                class="action-button"
                                @click="confirmDelete(scope.row)">
                                {{ $t('admin.common.delete') }}
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="pagination">
                    <el-pagination
                        v-if="banks.total > banks.per_page"
                        background
                        layout="prev, pager, next"
                        :total="banks.total"
                        :current-page="banks.current_page"
                        :page-size="banks.per_page"
                        @current-change="handlePaginate"
                    />
                </div>
            </el-card>
            <el-dialog
                :title="formTitle"
                :visible.sync="isShowForm"
                class="form-dialog"
            >
                <bank-form
                    :target="target"
                    :bank-status="bankStatus"
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
import BankForm from "./BankForm";
import DeleteConfirmDialog from './DeleteConfirmDialog';

export default {
    components: {BankForm, DeleteConfirmDialog},
    props: {
        banks: {
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
        keyword: {
            type: String,
            default() {
                return '';
            }
        },
        bankStatus: {
            type: Array,
            default() {
                return [];
            }
        },
        bankStatusEnum: {
            type: Object,
            default() {
                return {};
            }
        },
    },
    data() {
        return {
            search: this.keyword,
            banksDataSet: [],
            formTitle: '',
            isShowForm: false,
            isShowConfirmDelete: false,
            target: {},
        }
    },
    created() {
        this.prepareData(this.banks.data);
    },
    methods: {
        prepareData(data) {
            this.banksDataSet = this._.map(data, bank => {
                bank.status_name = this._.find(this.bankStatus, status => status.value === bank.status).name;
                return bank;
            })
        },
        confirmDelete(target) {
            this.target = target;
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
                data.status_name = this._.find(this.bankStatus, status => status.value === data.status).name;
                this.banksDataSet.push(data);
            } else {
                this.banksDataSet = this._.map(this.banksDataSet, (record) => {
                    if (record.id === data.id) {
                        data.status_name = this._.find(this.bankStatus, status => status.value === data.status).name;
                        return data;
                    } else {
                        record.status_name = this._.find(this.bankStatus, status => status.value === record.status).name;
                        return record;
                    }
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
            window.location = this.route('admin.banks_index', params);
        },
        async handleDelete() {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            try {
                const uri = this.route('admin.ajax_delete_bank', {id: this.target.id});
                await this.Request.delete(uri);
                this.banksDataSet.splice(this._.findIndex(this.banksDataSet, this.target), 1);
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
        showForm(target) {
            this.formTitle = this._.isEmpty(target)
                ? this.$t('admin.common.create')
                : this.$t('admin.common.edit') + ' #' + target.id;
            this.target = target;
            this.isShowForm = true
        },
        showAttributes(target) {
            this.target = target;
            this.isShowAttributes = true
        },
        hideAttributes() {
            this.isShowAttributes = false;
        }
    }
}
</script>
