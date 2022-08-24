<template>
    <el-container class="manager-screen">
        <el-header>
            <div class="left">
                <el-button
                        type="success"
                        icon="el-icon-refresh"
                        @click="handlePaginate(1)"
                >
                    Reload
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
                    :data="transfersDataSet"
                    :default-sort="{prop: '_lft', order: 'ascending' }"
                    style="width: 100%">
                    <el-table-column
                            prop="id"
                            label="Mã"
                            sortable
                    />
                    <el-table-column
                        prop="amount"
                        label="Số Tiền"
                        sortable/>
                    <el-table-column
                        prop="trans_id"
                        label="Mã giao dịch"
                        sortable/>
                    <el-table-column
                        prop="receiver_account"
                        label="Người nhận"
                        sortable/>
                    <el-table-column
                        prop="receiver_bank_id"
                        label="Mã ngân hàng nhận"
                        sortable/>
                    <el-table-column
                        prop="reason"
                        label="Nội Dung"
                        sortable/>
                    <el-table-column
                        prop="status_name"
                        label="Trạng thái"
                        sortable/>
                    <el-table-column
                        prop="created_at"
                        label="Tạo lúc"
                        sortable/>
                    <el-table-column
                        prop="updated_at"
                        label="Cập nhật cuối lúc"
                        sortable/>
                    <el-table-column
                            fixed="right"
                            :label="$t('admin.common.action')"
                    >
                        <template slot-scope="scope">
                            <el-button
                                plain
                                type="success"
                                :title="'Reset'"
                                size="mini"
                                icon="el-icon-refresh"
                                class="action-button"
                                @click="handleReset(scope.row)"
                            >
                                Reset
                            </el-button>
                            <el-button
                                plain
                                type="warning"
                                :title="'Callback'"
                                size="mini"
                                icon="el-icon-phone-outline"
                                class="action-button"
                                @click="handleCallback(scope.row)"
                            >
                               Callback
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
                <div class="pagination">
                    <el-pagination
                            v-if="transfers.total > transfers.per_page"
                            background
                            layout="prev, pager, next"
                            :total="transfers.total"
                            :current-page="transfers.current_page"
                            :page-size="transfers.per_page"
                            @current-change="handlePaginate"
                    />
                </div>
            </el-card>
            <el-dialog
                :title="formTitle"
                :visible.sync="isShowForm"
                class="form-dialog"
            >
                <history-transfer-form
                    :target="target"
                    :transaction-status="transactionStatus"
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
    import HistoryTransferForm from "./HistoryTransferForm";
    import DeleteConfirmDialog from './DeleteConfirmDialog';

    export default {
        components: { HistoryTransferForm, DeleteConfirmDialog },
        props: {
            transfers: {
                type: Object,
                default() {
                    return {};
                },
            },
            user: {
                type: Object,
                default() {
                    return {};
                },
            },
            keyword: {
                type: String,
                default: '',
            },
            transactionStatus: {
                type: Array,
                default() {
                    return [];
                }
            },
            transactionStatusEnum: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        data(){
            return {
                search: this.keyword,
                transfersDataSet: [],
                formTitle: '',
                isShowForm: false,
                isShowConfirmDelete: false,
                target: {},
            }
        },
        created() {
            this.prepareData(this.transfers.data);
        },
        methods: {
            prepareData(data) {
                this.transfersDataSet = this._.map(data, transaction => {
                    transaction.status_name = this._.find(this.transactionStatus, status => status.value === transaction.status).name;
                    return transaction;
                })
            },
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
                    this.transfersDataSet.push(data);
                    this.prepareData(this.transfersDataSet);
                } else {
                    this.transfersDataSet =  this._.map(this.transfersDataSet, (record) => {
                        return (record.id === data.id) ? data : record;
                    });
                    this.prepareData(this.transfersDataSet);
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
                window.location = this.route('admin.history_transfers_index', params);
            },
            async handleCallback() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_history_callback', { id: this.target.id});
                    await this.Request.post(uri);
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: 'Gửi lại callback thành công',
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
            async handleDelete() {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_delete_history_transfer', { id: this.target.id});
                    await this.Request.delete(uri);
                    this.transfersDataSet.splice(this._.findIndex(this.transfersDataSet, this.target), 1);
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
            async handleReset(target) {
                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });
                try {
                    const uri = this.route('admin.ajax_reset_history_transfer', { id: target.id});
                    const response = await this.Request.post(uri);
                    this.$notify({
                        type: 'success',
                        title: this.$t('admin.common.success'),
                        message: 'Reset Thành công',
                    });

                    this.onSuccess(response.data, 'edit');
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
