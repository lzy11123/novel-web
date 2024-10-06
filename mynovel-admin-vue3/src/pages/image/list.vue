<template>
    <el-container class="bg-white rounded" :style="{ height: h + 'px' }">
        <el-header class="image-header">
            <el-button type="primary" size="small" @click="onOpenDrawer" v-permission="['createImageClass']">
                新增图库分类</el-button>

            <el-button type=" warning" size="small" @click="onOpenUpload" v-permission="['uploadImage']">
                上传图片</el-button>

        </el-header>
        <el-container>
            <ImageAside ref="ImageAsideRef" @change="onAsideChange"></ImageAside>
            <ImageMain ref="ImageMainRef"></ImageMain>
        </el-container>
    </el-container>
</template>

<script setup>
import { ref } from 'vue'

import ImageAside from '~/components/ImageAside.vue'
import ImageMain from '~/components/ImageMain.vue'

// 窗口高度
const windowHeight = window.innerHeight || document.body.clientHeight;
// 容器高度
const h = windowHeight - 64 - 44 - 40;

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


</script>

<style scoped>
.image-header {
    @apply flex items-center;
    border-bottom: 1px solid #eee;
}
</style>