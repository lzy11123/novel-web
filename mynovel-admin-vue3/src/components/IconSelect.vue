<template>
    <div class="flex items-center">
        <el-icon v-if="modelValue" :size="20" class="mr-2">
            <component :is='modelValue'></component>
        </el-icon>
        <el-select filterable :modelValue="modelValue" placeholder="请选择图标" @change="handleChange">
            <el-option v-for="item in icons" :key="item" :label="item" :value="item">

                <div class="flex items-center justify-between">
                    <el-icon>
                        <component :is='item'></component>
                    </el-icon>
                    <span class="text-gray-500">{{ item }}</span>
                </div>

            </el-option>
        </el-select>
    </div>
</template>

<script setup>
import { ref } from 'vue'

import * as IconList from '@element-plus/icons-vue'

defineProps({
    modelValue: String,
})

const icons = ref(Object.keys(IconList));


const emit = defineEmits(['update:modelValue'])


const handleChange = (icon) => {
    // console.log(icon);
    emit('update:modelValue', icon)
}

</script>