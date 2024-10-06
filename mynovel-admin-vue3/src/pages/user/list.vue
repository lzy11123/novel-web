<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getUserList']">

        <!-- 搜索 -->
        <Search :model="searchForm" @search="getData" @reset="onResetSearchForm">
            <SearchItem label="关键词">
                <el-input v-model="searchForm.keyword" placeholder="手机号/邮箱/用户昵称" clearable />
            </SearchItem>
            <template #show>
                <SearchItem label="会员等级">
                    <el-select v-model="searchForm.user_level_id" placeholder="请选择会员等级">
                        <el-option v-for="item in user_level" :key="item.id" :label="item.name" :value="item.id" />
                    </el-select>
                </SearchItem>
            </template>
        </Search>

        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createUser'" @refresh="onRefresh"></ListHeader>

        <el-table :data="tableData" stripe style="width: 100%">
            <el-table-column label="用户信息">
                <template #default="scope">
                    <div class="flex items-center">
                        <el-avatar :size="40" :src="scope.row.avatar">
                            <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                        </el-avatar>
                        <div class="ml-3">
                            <h6>{{ scope.row.username }}</h6>
                            <small>ID: {{ scope.row.id }}</small>
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="所属会员等级">
                <template #default="{ row }">
                    {{ row.userLevel?.name || '-' }}
                </template>
            </el-table-column>
            <el-table-column label="状态" width="120" v-permission="['updateUserStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" :disabled="row.super == 1" />
                </template>
            </el-table-column>
            <el-table-column label="登录注册时间" width="300">
                <template #default="{ row }">
                    <div class="text-xs">近期登录时间：{{ row.update_time }}</div>
                    <div class="text-xs text-gray-400 mt-2">注册时间：{{ row.create_time }}</div>
                </template>
            </el-table-column>
            <el-table-column label="操作" align="center" width="200">
                <template #default="scope">
                    <span v-if="scope.row.super == 1">暂无操作</span>
                    <div v-else>
                        <el-button text type="primary" size="small" @click="onEdit(scope.row)"
                            v-permission="['updateUser']">修改</el-button>

                        <el-popconfirm title="是否要删除该用户?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(scope.row.id)">
                            <template #reference>
                                <el-button text type="primary" size="small" v-permission="['deleteUser']">删除</el-button>
                            </template>
                        </el-popconfirm>
                    </div>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex items-center justify-center mt-5">
            <el-pagination background layout="prev, pager, next" :total="total" :current-page="currentPage"
                :page-size="limit" @current-change="onChangePage" />
        </div>
        <FormDrawer ref="formDrawer" :title="drawerTitle" @submit="onSubmit">
            <el-form ref="formRef" :model="form" :rules="rules" label-width="100px">
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="form.username" placeholder="请输入用户名" />
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="form.password" placeholder="请输入密码" />
                </el-form-item>
                <el-form-item label="头像" prop="avatar">
                    <ChooseImage v-model="form.avatar"></ChooseImage>
                </el-form-item>
                <el-form-item label="昵称" prop="nickname">
                    <el-input v-model="form.nickname" placeholder="请输入昵称" />
                </el-form-item>
                <el-form-item label="手机号" prop="phone">
                    <el-input v-model="form.phone" placeholder="请输入手机号" />
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="form.email" placeholder="请输入邮箱" />
                </el-form-item>
                <el-form-item label="会员等级" prop="user_level_id">
                    <el-select v-model="form.user_level_id" placeholder="请选择会员等级">
                        <el-option v-for="item in user_level" :key="item.id" :label="item.name" :value="item.id" />
                    </el-select>
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
            </el-form>
        </FormDrawer>


    </el-card>
</template>
<script setup>
import { ref } from 'vue';
import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';
import ChooseImage from '~/components/ChooseImage.vue';
import Search from '~/components/Search.vue';
import SearchItem from '~/components/SearchItem.vue';

import { getUserList, createUser, updateUser, deleteUser, changeUserStatus } from '~/api/user.js'

import { useInitTable, useInitForm } from '~/composables/useCommon.js'

const user_level = ref([]);

const {
    loading,
    searchForm,
    onResetSearchForm,
    tableData,
    currentPage,
    total,
    limit,
    getData,
    onRefresh,
    onChangePage,
    onDelete,
    onStatusChange
} = useInitTable({
    searchForm: {
        keyword: '',
        limit: 10,
        user_level_id: '',
    },
    getList: getUserList,
    onGetListSuccess: (res) => {
        tableData.value = res.list;
        // console.log(tableData);
        total.value = res.totalCount;
        user_level.value = res.user_level
    },
    delete: deleteUser,
    changeStatus: changeUserStatus
});




const {
    formDrawer,
    formRef,
    form,
    drawerTitle,
    rules,
    onSubmit,
    onCreate,
    onEdit } = useInitForm({
        form: {
            username: "",
            password: "",
            status: "1",
            nickname: "",
            phone: "",
            email: "",
            avatar: "",
            user_level_id: '',
        },
        rules: {
            username: [
                { required: true, message: '用户名称不能为空', trigger: 'blur' },
            ],
            user_level_id: [
                { required: true, message: '会员等级不能为空', trigger: 'blur' },
            ],
        },
        getData,
        update: updateUser,
        create: createUser,

    });
</script>