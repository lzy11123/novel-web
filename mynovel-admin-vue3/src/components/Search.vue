<template>

    <el-form ref="searchFormRef" :model="model" label-width="100px" class="mb-3">
        <el-row :gutter="20">
            <slot></slot>
            <template v-if="showSearch">
                <slot name="show"></slot>
            </template>
            <el-col :span="8" :offset="showSearch ? 0 : 8">
                <div class="flex items-center justify-end">
                    <el-button type="primary" @click="$emit('search')" size="small">搜索</el-button>
                    <el-button type="danger" @click="$emit('reset')" size="small">重置</el-button>
                    <el-button type="primary" text size="small" v-if="hasShowSearch" @click="showSearch = !showSearch">
                        {{ showSearch ? '收起' : '展开' }}
                        <el-icon class="ml-1">
                            <component :is='showSearch ? "ArrowUp" : "ArrowDown"'></component>
                        </el-icon>
                    </el-button>
                </div>
            </el-col>
        </el-row>
    </el-form>


</template>

<script setup>
import { ref, useSlots } from 'vue'

defineProps({
    model: Object
})

defineEmits(['search', 'reset'])

const showSearch = ref(false);

const solts = useSlots();
const hasShowSearch = ref(!!solts.show)

</script>