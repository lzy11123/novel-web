<template>
    <!-- 刷新|新增 -->
    <div class="flex items-center justify-between mb-3">
        <div>
            <template v-if="btns.includes('create')">
                <el-button v-if="permissionCreate" v-permission="[permissionCreate]" type="primary" size="small"
                    @click="$emit('create')">新增
                </el-button>
                <el-button v-else type="primary" size="small" @click="$emit('create')">新增
                </el-button>
            </template>


            <template v-if="btns.includes('delete')">
                <el-popconfirm title="是否要删除选中内容?" confirm-button-text="确定" cancel-button-text="取消"
                    @confirm="$emit('delete')">
                    <template #reference>
                        <el-button v-if="permissionDelete" v-permission="[permissionDelete]" type="danger"
                            size="small">批量删除
                        </el-button>
                        <el-button v-else type="danger" size="small">批量删除
                        </el-button>
                    </template>
                </el-popconfirm>

            </template>
            <!-- <el-button v-if="btns.includes('create')"  type="primary" size="small"
                    @click="$emit('create')">新增
                </el-button> -->
            <!-- <el-popconfirm v-if="btns.includes('delete')" title="是否要删除选中内容?" confirm-button-text="确定"
                cancel-button-text="取消" @confirm="$emit('delete')">
                <template #reference>
                    <el-button type="danger" size="small">批量删除
                    </el-button>
                </template>
            </el-popconfirm> -->

            <slot></slot>
        </div>
        <div>
            <el-tooltip v-if="btns.includes('refresh')" effect="dark" content="刷新数据" placement="bottom-start">
                <el-icon class="cursor-pointer" :size="18" @click="$emit('refresh')">
                    <Refresh />
                </el-icon>
            </el-tooltip>
            <el-tooltip v-if="btns.includes('download')" effect="dark" content="导出数据" placement="bottom-start">
                <el-icon class="cursor-pointer ml-2" :size="18" @click="$emit('download')">
                    <Download />
                </el-icon>
            </el-tooltip>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    layout: {
        type: String,
        default: 'create,refresh'
    },
    permissionCreate: {
        type: String,
        default: ''
    },
    permissionDelete: {
        type: String,
        default: ''
    },
})
const btns = computed(() => {
    return props.layout.split(',')
})
defineEmits(['create', 'refresh', 'delete', 'download'])

</script>