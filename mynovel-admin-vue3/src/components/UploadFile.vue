<template>
    <el-upload drag :action="uploadImageAction" multiple :headers="{ token }" name="img" :data="data"
        :on-success="uploadSuccess" :on-error="unploadError">
        <el-icon class="el-icon--upload">
            <upload-filled />
        </el-icon>
        <div class="el-upload__text">
            拖拽图片到此处或者 <em>点击上传</em>
        </div>
        <template #tip>
            <div class="el-upload__tip">
                图片文件大小不能超过10m
            </div>
        </template>
    </el-upload>
</template>
<script setup>
import { uploadImageAction } from '~/api/image.js'
import { Toast, getToken } from '~/composables/until.js'

defineProps({
    data: Object
})

const token = getToken();
const emit = defineEmits(['success'])

// 上传成功
const uploadSuccess = (response, uploadFile, uploadFiles) => {
    // console.log(response)
    emit('success', {
        response, uploadFile, uploadFiles
    })
}

// 上传失败
const unploadError = (error, uploadFile, uploadFiles) => {
    // console.log(error)
    let msg = JSON.parse(error.message).msg || '上传失败'
    Toast(msg)
}
</script>