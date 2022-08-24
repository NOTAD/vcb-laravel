<template>
    <el-dialog
            :title="title"
            :visible="visible"
            center
            class="delete-dialog"
            :onclose="onCancel"
    >
        <p class="text-center">{{ this.$t('admin.message.confirm_delete_message')}}</p>
        <span slot="footer" class="dialog-footer">
            <el-button type="danger" @click="onConfirm">{{ this.$t('admin.common.confirm')}}</el-button>
            <el-button @click="onCancel">{{ this.$t('admin.common.cancel')}}</el-button>
        </span>
    </el-dialog>
</template>
<script>
    export default {
        props: {
            title: {
                Type: String,
                default: '',
            },
            visible: {
                Type: Boolean,
                default: false,
            },
            onCancel: {
                Type: Function,
                default() {
                    return null;
                },
            },
            onConfirm: {
                Type: Function,
                default() {
                    return null;
                },
            },
        },
    }
</script>
