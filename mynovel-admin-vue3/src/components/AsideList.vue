<template>
    <div class="aside-list" :class="{ 'active': active }">
        <span class="truncate">
            <slot></slot>
        </span>
        <el-button class="ml-auto px-1" text type="primary" size="small" @click.stop="$emit('edit')" v-permission="['updateImageClass']">
            <el-icon :size="16">
                <Edit />
            </el-icon>
        </el-button>
        <span @click.stop="() => { }">
            <el-popconfirm title="是否要删除该分类?" confirm-button-text="确认" cancel-button-text="取消"
                @confirm="$emit('delete')">
                <template #reference>
                    <el-button class="px-1" text type="primary" size="small" v-permission="['deleteImageClass']">
                        <el-icon :size="16">
                            <Close />
                        </el-icon>
                    </el-button>
                </template>
            </el-popconfirm>
        </span>
    </div>

</template>

<script setup>
defineProps({
    active: {
        type: Boolean,
        default: false
    }
})


defineEmits(['edit', 'delete'])

</script>

<style scoped>
.aside-list {
    border-bottom: 1px solid #f4f4f4;
    @apply flex items-center p-3 text-sm text-gray-600 cursor-pointer;
}

.aside-list:hover {
    @apply bg-blue-50;
}

.aside-list.active {
    @apply bg-blue-50;
}
</style>
