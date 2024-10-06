<template>
    <div class="flex flex-wrap">
        <div v-if="modelValue">
            <!-- <el-image v-if="typeof modelValue == 'string'" :src="modelValue" fit="cover"
                class="w-[100px] h-[100px] mr-2"></el-image> -->

            <div v-if="typeof modelValue == 'string'" class="flex flex-wrap">
                <div class="relative mx-1 mb-2 w-[100px] h-[100px]">
                    <el-icon class="absolute top-[5px] right-[5px] bg-white rounded-full cursor-pointer"
                        style="z-index:10" @click="removeOneImage">
                        <CircleClose />
                    </el-icon>
                    <el-image :src="modelValue" fit="cover" class="w-[100px] h-[100px] mr-2">
                    </el-image>
                </div>
            </div>



            <div v-else class="flex flex-wrap">
                <div class="relative mx-1 mb-2 w-[100px] h-[100px]" v-for="(url, index) in modelValue" :key="index">
                    <el-icon class="absolute top-[5px] right-[5px] bg-white rounded-full cursor-pointer"
                        style="z-index:10" @click="removeImage(url)">
                        <CircleClose />
                    </el-icon>
                    <el-image :src="url" fit="cover" class="w-[100px] h-[100px] mr-2">
                    </el-image>
                </div>
            </div>

        </div>
        <div class="choose-image-btn" v-if="preview" @click="openDialog">
            <el-icon :size="25" class="text-gray-500">
                <Plus />
            </el-icon>
        </div>

        <el-dialog v-model="dialogVisible" title="选择图片" width="80%" top="5vh">
            <el-container class="bg-white rounded" style="height:70vh">
                <el-header class="image-header">
                    <el-button type="primary" size="small" @click="onOpenDrawer">新增图库分类</el-button>
                    <el-button type="warning" size="small" @click="onOpenUpload">上传图片</el-button>
                </el-header>
                <el-container>
                    <ImageAside ref="ImageAsideRef" @change="onAsideChange"></ImageAside>
                    <ImageMain :limit="limit" openChoose ref="ImageMainRef" @choose="onChooseImage"></ImageMain>
                </el-container>
            </el-container>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="closeDialog">取消</el-button>
                    <el-button type="primary" @click="submit">确定</el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>
<script setup>
import { ref } from 'vue'

import ImageAside from '~/components/ImageAside.vue'
import ImageMain from '~/components/ImageMain.vue'
import { Toast } from '~/composables/until.js';


const ImageAsideRef = ref(null);
const ImageMainRef = ref(null);
// 打开添加分类弹框
const onOpenDrawer = () => ImageAsideRef.value.onOpenDrawer();
// 打开上传图片弹框
const onOpenUpload = () => ImageMainRef.value.onOpenUploadDrawer();

// 切换图库
const onAsideChange = (image_class_id) => {
    // console.log(image_class_id)
    ImageMainRef.value.loadData(image_class_id)
}



const dialogVisible = ref(false);

const callbackFunction = ref(null);

const openDialog = (callback = null) => {
    callbackFunction.value = callback
    dialogVisible.value = true;
}

const closeDialog = () => {
    dialogVisible.value = false;
}


const props = defineProps({
    modelValue: [String, Array],
    limit: {
        type: Number,
        default: 1,
    },
    preview: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['update:modelValue'])

let urls = [];
const onChooseImage = (e) => {
    // console.log(e)
    urls = e.map(o => o.url)
    // console.log(urls)
}

// 返回数据
const submit = () => {
    let value = [];

    if (props.limit == 1) {
        value = urls[0];
    } else {
        value = props.preview ? [...props.modelValue, ...urls] : [...urls]
        if (value.length > props.limit) {
            let limit = props.preview ? props.limit - props.modelValue.length : props.limit
            return Toast(`最多还能选择${limit}张`, 'error')
        }

    }

    if (value && props.preview) {
        emit('update:modelValue', value)
    }

    // 方法回调
    if (!props.preview && typeof callbackFunction.value == 'function') {
        callbackFunction.value(value);
    }

    closeDialog();
}

// 移除图片
const removeImage = (url) => {
    let urls = props.modelValue.filter(u => u != url);
    emit('update:modelValue', urls)
}


const removeOneImage = () => {
    emit('update:modelValue', '')
}


defineExpose({
    openDialog,
    closeDialog
})



</script>
<style scoped>
.choose-image-btn {
    @apply w-[100px] h-[100px] rounded border flex justify-center items-center cursor-pointer hover: bg-gray-100;
}
</style>