<template>
    <el-card shadow="never" v-loading="loading">

        <!-- 搜索 -->
        <el-form ref="searchFormRef" :model="searchForm" label-width="80px" class="mb-3">
            <el-row :gutter="20">
                <el-col :span="8" :offset="0">
                    <el-form-item label="关键词" prop="title">
                        <el-input v-model="searchForm.keyword" placeholder="管理员昵称" clearable />
                    </el-form-item>

                </el-col>
                <el-col :span="8" :offset="8">
                    <div class="flex items-center justify-end">
                        <el-button type="primary" @click="getData" size="small">搜索</el-button>
                        <el-button type="danger" @click="onResetSearchForm" size="small">重置</el-button>
                    </div>
                </el-col>
            </el-row>
        </el-form>

        <!-- 刷新|新增 -->
        <div class="flex items-center justify-between mb-3">
            <el-button type="primary" @click="onCreate">新增</el-button>
            <el-tooltip effect="dark" content="刷新" placement="bottom-start">
                <el-icon class="cursor-pointer" :size="20" @click="onRefresh">
                    <Refresh />
                </el-icon>
            </el-tooltip>
        </div>
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
            <el-table-column label="状态" width="120">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" :loading="row.statusLoading" :disabled="row.super == 1" />
                </template>
            </el-table-column>
            <el-table-column prop="create_time" label="发布时间" width="300" />
            <el-table-column label="操作" align="center" width="200">
                <template #default="scope">
                    <span v-if="scope.row.super == 1">暂无操作</span>
                    <div v-else>
                        <el-button text type="primary" size="small" @click="onEdit(scope.row)">修改</el-button>

                        <el-popconfirm title="是否要删除该管理员?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(scope.row.id)">
                            <template #reference>
                                <el-button text type="primary" size="small">删除</el-button>
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
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
            </el-form>
        </FormDrawer>
    </el-card>
</template>
<script setup>
import { computed, reactive, ref } from 'vue'

import FormDrawer from '~/components/FormDrawer.vue';
import ChooseImage from '~/components/ChooseImage.vue'

import { getManagerList, updateManagerStatus, createManager, updateManager, deleteManager } from '~/api/manager.js'
import { Toast } from '~/composables/until';

const loading = ref(false)


const searchForm = reactive({
    limit: 10,
    keyword: '',
})



const tableData = ref([])
const roles = ref([]);
// 分页
const currentPage = ref(1)
const total = ref(0)
const limit = ref(10)

function getData(p = null) {

    if (typeof p == 'number') {
        currentPage.value = p;
    }

    loading.value = true;
    getManagerList(currentPage.value, searchForm).then(res => {
        // console.log(res);
        // tableData.value = res.list;

        tableData.value = res.list.map(item => {
            item.statusLoading = false;
            return item;
        })
        roles.value = res.roles;
        total.value = res.totalCount;

    }).finally(() => {
        loading.value = false;
    })
}

getData();

const onChangePage = (page) => {
    getData(page)
}

// 搜索重置
const onResetSearchForm = () => {
    searchForm.keyword = '';
    getData();
}


// 刷新
const onRefresh = () => {
    location.reload();
}


// 删除管理员
const onDelete = (id) => {
    console.log(id)
    loading.value = true;
    deleteManager(id).then(res => {
        Toast('删除成功');
        getData();
    }).finally(() => {
        loading.value = false;
    })
}


// 表单内容
const formDrawer = ref(null);
const formRef = ref(null);
const form = reactive({
    username: '',
    password: '',
    role_id: null,
    status: 1,
    avatar: ''
})
const editId = ref(0)
const drawerTitle = computed(() => {
    return editId.value ? '修改' : '新增'
})

const rules = reactive({
    username: [
        { required: true, message: '用户名不能为空', trigger: 'blur' },
    ],
    // password: [
    //     { required: true, message: '密码不能为空', trigger: 'blur' },
    // ],
})

// 提交
const onSubmit = () => {
    formRef.value.validate((valid) => {
        // console.log(valid);

        if (!valid) return;
        formDrawer.value.showLoading();

        const fun = editId.value ? updateManager(editId.value, form) : createManager(form)

        fun.then(res => {
            // console.log(res);

            Toast(drawerTitle.value + '成功')
            // 修改刷新当前，新增刷新第一页
            getData(editId.value ? false : 1);
            formDrawer.value.close();
        }).finally(() => {
            formDrawer.value.hideLoading();
        })
    })
}

// 重置表单
const onResetForm = (row = false) => {
    if (formRef.value) formRef.value.clearValidate();

    if (row) {
        for (const key in form) {
            form[key] = row[key]
        }
    }

}

// 新增打开弹框
const onCreate = () => {
    editId.value = 0;
    onResetForm({
        username: '',
        password: '',
        role_id: null,
        status: 1,
        avatar: ''
    });
    formDrawer.value.open();
}

// 修改打开弹框
const onEdit = (item) => {
    editId.value = item.id;
    onResetForm(item)
    formDrawer.value.open();
}

// 修改状态
const onStatusChange = (status, row) => {
    // console.log(status)
    row.statusLoading = true;
    updateManagerStatus(row.id, status).then(res => {
        row.status = status;
        Toast('状态修改成功');
    }).finally(() => {
        row.statusLoading = false;
    })
}

</script>