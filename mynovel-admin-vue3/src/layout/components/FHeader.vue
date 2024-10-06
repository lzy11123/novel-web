<template>
    <div class="f-header">
        <span class="logo">
            <!-- <el-icon class="mr-1">
                <ElemeFilled />
            </el-icon> -->
            后台管理系统
        </span>
        <el-icon class="icon-btn" @click="$store.commit('handleAsideWidth')">
            <Fold v-if="$store.state.asideWidth == '200px'" />
            <Expand v-else />
        </el-icon>

        <el-tooltip effect="dark" content="刷新" placement="bottom-start">
            <el-icon class="icon-btn" @click="onRefresh">
                <Refresh />
            </el-icon>
        </el-tooltip>

        <div class="dropdown">
            <el-tooltip effect="dark" :content="!isFullscreen ? '全屏' : '退出全屏'" placement="bottom-start">
                <el-icon class="icon-btn" @click="toggle">
                    <FullScreen v-if="!isFullscreen" />
                    <Aim v-else />
                </el-icon>
            </el-tooltip>
            <el-dropdown class="mx-2" @command="handCommand">
                <span class="el-dropdown-link">
                    <el-avatar class="mr-2" :size="25" :src="$store.state.user.avatar" />
                    {{ $store.state.user.username }}
                    <el-icon class="el-icon--right">
                        <arrow-down />
                    </el-icon>
                </span>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item command="repassword">修改密码</el-dropdown-item>
                        <el-dropdown-item command="logout">退出登录</el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>

        <FormDrawer ref="formDrawerRef" :title="'修改密码'" :destroyOnClose="true" @submit="onSubmitPwd">
            <el-form ref="pwdFormRef" :rules="rules" :model="form" label-width="80px">
                <el-form-item prop="oldpassword" label="旧密码">
                    <el-input type="password" show-password v-model="form.oldpassword" placeholder="请输入旧密码"></el-input>
                </el-form-item>
                <el-form-item prop="password" label="新密码">
                    <el-input type="password" show-password v-model="form.password" placeholder="请输入新密码"></el-input>
                </el-form-item>
                <el-form-item prop="repassword" label="确认密码">
                    <el-input type="password" show-password v-model="form.repassword" placeholder="请输入确认密码"></el-input>
                </el-form-item>
            </el-form>
        </FormDrawer>

    </div>
</template>

<script setup>
// 组件
import FormDrawer from '~/components/FormDrawer.vue'
// 全屏插件
import { useFullscreen } from '@vueuse/core'

import { useRepassword, useLogout } from '~/composables/useManager.js'

const {
    // 是否全屏状态
    isFullscreen,
    // 切换方法
    toggle
} = useFullscreen();

const {
    formDrawerRef,
    pwdFormRef,
    form,
    rules,
    openFormDrawer,
    onSubmitPwd
} = useRepassword();

const {
    onLogout
} = useLogout();

// 下拉菜单监听事件
const handCommand = (e) => {
    switch (e) {
        case "logout":
            onLogout();
            break;
        case "repassword":
            //  console.log('修改密码');
            openFormDrawer();
            break;
    }
}

// 刷新
const onRefresh = () => location.reload();


</script>

<style scoped>
.f-header {
    @apply flex items-center bg-indigo-700 text-light-50 fixed top-0 left-0 right-0;
    height: 64px;
    z-index: 1000;
}

.logo {
    width: 200px;
    @apply flex justify-center items-center text-xl font-thin;
}

.icon-btn {
    @apply flex items-center justify-center text-light-50;
    width: 42px;
    height: 64px;
    cursor: pointer;
}

.icon-btn:hover {
    @apply bg-indigo-600;
}

.f-header .dropdown {
    @apply ml-auto flex items-center justify-center;
    height: 64px;
    cursor: pointer;
}

.el-dropdown-link {
    @apply flex items-center text-light-50;
}
</style>