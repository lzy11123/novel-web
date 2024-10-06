<template>
    <el-drawer v-model="showDrawer" :title="title" :size="size" :close-on-click-modal="false"
        :destroy-on-close="destroyOnClose">
        <div class="formDrawer">
            <div class="body pr-1">
                <slot></slot>
            </div>
            <div class="actions">
                <el-button type="primary" v-if="updateForm" v-permission="[updateForm]" :loading="loading"
                    @click="submit">{{ confirmText }}</el-button>
                <el-button type="primary" v-else :loading="loading" @click="submit">{{ confirmText }}</el-button>

                <el-button type="default" @click="close">取消</el-button>
            </div>
        </div>
    </el-drawer>
</template>

<script setup>
import { ref } from 'vue'


const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: '45%'
    },
    confirmText: {
        type: String,
        default: '确定'
    },
    destroyOnClose: {
        type: Boolean,
        default: false
    },
    updateForm: {
        type: String,
        default: ''
    }
})

const showDrawer = ref(false);
const loading = ref(false);

// 打开
const open = () => showDrawer.value = true;
// 关闭
const close = () => showDrawer.value = false;
// 显示加载中
const showLoading = () => loading.value = true;
// 关闭加载
const hideLoading = () => loading.value = false;
// 提交
const emit = defineEmits(['submit'])
const submit = () => emit('submit');

defineExpose({
    open,
    close,
    showLoading,
    hideLoading
})

</script>

<style scoped>
.formDrawer {
    width: 100%;
    height: 100%;
    @apply flex flex-col;
    position: relative;
}

.formDrawer .body {
    @apply flex-1;
    overflow-y: auto
}

.formDrawer .actions {
    height: 50px;
    @apply mt-auto;
}

.formDrawer .body::-webkit-scrollbar {
    width: 0;
}

/*设置滚动条样式*/
.formDrawer .body::-webkit-scrollbar {
    width: 3px;
    height: 8px;
    background-color: rgba(210, 210, 210, 0.48);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 0;
}

.formDrawer .body::-webkit-scrollbar-thumb {
    background-color: rgba(123, 121, 121, 0.2);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 0;
}
</style>