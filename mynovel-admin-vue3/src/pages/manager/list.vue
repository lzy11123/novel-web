<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getManagerList']">

        <!-- 搜索 -->
        <Search :model="searchForm" @search="getData" @reset="onResetSearchForm">
            <SearchItem label="关键词">
                <el-input v-model="searchForm.keyword" placeholder="管理员昵称" clearable />
            </SearchItem>
        </Search>



        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createManager'" @refresh="onRefresh"></ListHeader>

        <el-table :data="tableData" stripe style="width: 100%">
            <el-table-column label="管理员">
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
            <el-table-column label="所属管理员">
                <template #default="{ row }">
                    {{ row.role?.name || '-' }}
                </template>
            </el-table-column>
            <el-table-column label="状态" width="120" v-permission="['updateManagerStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" :disabled="row.super == 1" />
                </template>
            </el-table-column>
            <el-table-column prop="create_time" label="创建时间" width="300" />
            <el-table-column label="操作" align="center" width="200">
                <template #default="scope">
                    <span v-if="(scope.row.super == 1 && $store.state.user.super != 1)">
                        无权限
                        <!-- <el-button text type="primary" size="small" @click="onEdit(scope.row)">修改</el-button> -->
                    </span>
                    <div v-else>
                        <el-button text type="primary" size="small" @click="onEdit(scope.row)"
                            v-permission="['updateManager']">修改</el-button>

                        <el-popconfirm title="是否要删除该管理员?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(scope.row.id)">
                            <template #reference>
                                <el-button text type="primary" size="small"
                                    v-permission="['deleteManager']">删除</el-button>
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
                <el-form-item label="所属角色" prop="role_id">
                    <el-select v-model="form.role_id" placeholder="请选择所属角色">
                        <el-option v-for="item in roles" :key="item.id" :label="item.name" :value="item.id" />
                    </el-select>
                    <!-- <el-checkbox-group v-model="form.role_id">
                        <el-checkbox v-for="item in roles" :key="item.id" :label="item.id" name="role_id">{{
                                item.role_name
                        }}</el-checkbox>
                    </el-checkbox-group> -->
                </el-form-item>

                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
            </el-form>
        </FormDrawer>
    </el-card>
</template>
<script setup>
import { ref } from 'vue'

import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';
import ChooseImage from '~/components/ChooseImage.vue';
import Search from '~/components/Search.vue';
import SearchItem from '~/components/SearchItem.vue';

import { getManagerList, changeManagerStatus, createManager, updateManager, deleteManager } from '~/api/manager.js'

import { useInitTable, useInitForm } from '~/composables/useCommon.js'



const roles = ref([]);

const { loading,
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
        keyword: ''
    },
    getList: getManagerList,
    delete: deleteManager,
    changeStatus: changeManagerStatus,
    onGetListSuccess: (res) => {
        // console.log(res);
        tableData.value = res.list.map(item => {
            item.statusLoading = false;
            return item;
        })
        roles.value = res.role;
        // console.log(roles.value);
        total.value = res.totalCount;
    }
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
            username: '',
            password: '',
            role_id: null,
            status: 1,
            avatar: ''
        },
        rules: {
            username: [
                { required: true, message: '用户名不能为空', trigger: 'blur' },
            ],
            role_id: [
                { required: true, message: '请选择角色', trigger: 'blur' },
            ],
            // password: [
            //     { required: true, message: '密码不能为空', trigger: 'blur' },
            // ],
        },
        getData,
        update: updateManager,
        create: createManager,

    });



</script>