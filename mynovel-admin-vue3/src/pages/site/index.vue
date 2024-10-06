<template>
    <el-container class="bg-white rounded" :style="{ height: h + 'px' }" v-permission="['getSite']">
        <el-form ref="formRef" :model="form" label-width="120px">
            <el-form-item label="title标题" prop="title">
                <el-input v-model="form.title" placeholder="请输入角色名称" class="w-3xl" />
            </el-form-item>
            <el-form-item label="网站关键词" prop="keywords">
                <el-input v-model="form.keywords" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="网站描述" prop="description">
                <el-input v-model="form.description" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="公司名" prop="company_name">
                <el-input v-model="form.company_name" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="网址" prop="url">
                <el-input v-model="form.url" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="手机" prop="mobile">
                <el-input v-model="form.mobile" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="电话" prop="tel">
                <el-input v-model="form.tel" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="QQ" prop="qq">
                <el-input v-model="form.qq" placeholder="请输入角色描述" class="w-3xl" />
            </el-form-item>
            <el-form-item label="logo" prop="logo_img">
                <ChooseImage v-model="form.logo_img"></ChooseImage>
            </el-form-item>
            <el-form-item label="logo2" prop="logo_img2">
                <ChooseImage v-model="form.logo_img2"></ChooseImage>
            </el-form-item>
            <el-form-item label="落款" prop="inscribe">
                <Editor :height="300" v-model="form.inscribe"></Editor>
            </el-form-item>
            <el-form-item label="">
                <el-button type="primary" :loading="loading" @click="submit"
                    v-permission="['updateSite']">确认无误，提交</el-button>
            </el-form-item>
        </el-form>
    </el-container>

</template>
    
<script setup>
import { ref, reactive } from 'vue'
import ChooseImage from '~/components/ChooseImage.vue';

import Editor from '~/components/Editor.vue';

import { getSite, createOrUpdateRule } from '~/api/site.js';
import { Toast } from '~/composables/until';
// 窗口高度
const windowHeight = window.innerHeight || document.body.clientHeight;
// 容器高度
const h = windowHeight - 64 - 44 - 40;

const formRef = ref(null);
const form = reactive({
    id: 1,
    title: '',
    keywords: '',
    description: '',
    company_name: '',
    url: '',
    mobile: '',
    tel: '',
    qq: '',
    logo_img: '',
    logo_img2: '',
    inscribe: '',
});

const loading = false;


const getData = () => {
    getSite().then(res => {
        if (res) {
            for (const key in res) {
                form[key] = res[key]
            }
        }
    })
}

getData();

const submit = () => {
    createOrUpdateRule(form).then(res => {
        Toast('提交成功')
        getData();
    })
}

</script>
    
<style  scoped>
:deep(.el-form-item__label) {
    /* display: inline-flex; */
    justify-content: flex-start;
    /* align-items: flex-start;
    flex: 0 0 auto;
    font-size: var(--el-form-label-font-size);
    color: var(--el-text-color-regular);
    height: 32px;
    line-height: 32px;
    padding: 0 12px 0 0;
    box-sizing: border-box; */
}

::-webkit-scrollbar {
    width: 3px;
    height: 8px;
    background-color: rgba(210, 210, 210, 0.48);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 0;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(123, 121, 121, 0.2);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 0;
}
</style>
    