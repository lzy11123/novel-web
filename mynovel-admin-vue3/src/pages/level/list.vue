<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getUserLevelList']">
        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createUserLevel'" @refresh="onRefresh"></ListHeader>

        <el-table :data="tableData" stripe style="width: 100%">
            <el-table-column prop="name" label="会员等级" />
            <el-table-column prop="discount" label="折扣率(%)" align="center" />
            <el-table-column prop="level" label="等级序号" align="center" />
            <el-table-column label="升级条件">
                <template #default="{ row }">
                    <p>累计消费满 {{ row.max_price }} 元</p>
                    <p>(并且)累计消费满 {{ row.max_times }} 次</p>
                </template>
            </el-table-column>
            <el-table-column label="状态" width="120" v-permission="['updateUserLevelStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" />
                </template>
            </el-table-column>
            <el-table-column label="操作" align="center">
                <template #default="scope">
                    <el-button text type="primary" size="small" @click="onEdit(scope.row)"
                        v-permission="['updateUserLevel']">修改</el-button>
                    <el-popconfirm title="是否要删除该会员等级?" confirm-button-text="确定" cancel-button-text="取消"
                        @confirm="onDelete(scope.row.id)">
                        <template #reference>
                            <el-button text type="primary" size="small"
                                v-permission="['deleteUserLevel']">删除</el-button>
                        </template>
                    </el-popconfirm>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex items-center justify-center mt-5">
            <el-pagination background layout="prev, pager, next" :total="total" :current-page="currentPage"
                :page-size="limit" @current-change="onChangePage" />
        </div>
        <FormDrawer ref="formDrawer" :title="drawerTitle" @submit="onSubmit">
            <el-form ref="formRef" :model="form" :rules="rules" label-width="80px">
                <el-form-item label="等级名称" prop="name">
                    <el-input v-model="form.name" placeholder="请输入角色名称" />
                </el-form-item>
                <el-form-item label="等级权重" prop="level">
                    <el-input v-model="form.level" type="number" style="width:50%" />
                </el-form-item>
                <el-form-item label="是否启用" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <el-form-item label="升级条件">
                    <div>
                        累计消费满 <el-input v-model="form.max_price" type="number" style="width:50%" placeholder="累计消费">
                            <template #append>
                                元
                            </template>
                        </el-input>
                        <small class="flex text-gray-400">设置会员等级所需要的累计消费必须大于等于0,单位:元</small>

                    </div>
                    <div>
                        累计消费满 <el-input v-model="form.max_times" type="number" style="width:50%" placeholder="累计次数">
                            <template #append>
                                次
                            </template>
                        </el-input>
                        <small class="flex text-gray-400">设置会员等级所需要的累计消费次数必须大于等于0</small>
                    </div>
                </el-form-item>
                <el-form-item label="折扣率%">
                    <el-input v-model="form.discount" type="number" style="width:50%">
                        <template #append>
                            %
                        </template>
                    </el-input>
                    <small class="flex text-gray-400">折扣率单位为百分比，如输入90，表示该会员等级的用户可以以商品原价的90%购买</small>
                </el-form-item>
            </el-form>
        </FormDrawer>


    </el-card>
</template>
<script setup>

import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';

import { getUserLevelList, createUserLevel, updateUserLevel, deleteUserLevel, changeUserLevelStatus } from '~/api/level.js'

import { useInitTable, useInitForm } from '~/composables/useCommon.js'

const {
    loading,
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
    getList: getUserLevelList,
    delete: deleteUserLevel,
    changeStatus: changeUserLevelStatus
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
            name: '',
            level: 100,
            status: 1,
            discount: 0,
            max_price: 0,
            max_times: 0
        },
        rules: {
            name: [
                { required: true, message: '会员等级名称不能为空', trigger: 'blur' },
            ],
        },
        getData,
        update: updateUserLevel,
        create: createUserLevel,

    });


</script>