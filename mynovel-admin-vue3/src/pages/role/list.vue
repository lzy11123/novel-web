<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getRoleList']">
        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createRole'" @refresh="onRefresh"></ListHeader>

        <el-table :data="tableData" stripe style="width: 100%">
            <el-table-column prop="name" label="角色名称" />
            <el-table-column prop="desc" label="角色描述" />
            <el-table-column label="状态" width="120" v-permission="['updateRoleStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" />
                </template>
            </el-table-column>
            <el-table-column label="操作" align="center" width="300">
                <template #default="scope">
                    <el-button text type="primary" size="small" @click="openSetRule(scope.row)" v-permission="['setRoleRules']">配置权限</el-button>
                    <el-button text type="primary" size="small" @click="onEdit(scope.row)" v-permission="['updateRole']">修改</el-button>
                    <el-popconfirm title="是否要删除该角色?" confirm-button-text="确定" cancel-button-text="取消"
                        @confirm="onDelete(scope.row.id)">
                        <template #reference>
                            <el-button text type="primary" size="small" v-permission="['deleteRole']">删除</el-button>
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
                <el-form-item label="角色名称" prop="name">
                    <el-input v-model="form.name" placeholder="请输入角色名称" />
                </el-form-item>
                <el-form-item label="角色描述" prop="desc">
                    <el-input v-model="form.desc" type="textarea" placeholder="请输入角色描述" />
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
            </el-form>
        </FormDrawer>


        <FormDrawer ref="setRuleFormDrawer" title="权限配置" @submit="onSetRuleSubmit">
            <el-tree-v2 ref="elTreeRef" :data="ruleList" @check="handleChange" :props="{
                label: 'name', children: 'child'
            }" show-checkbox node-key="id" :default-expanded-keys="defaultExpandedKeys" :check-strictly="checkStrictly"
                :height="treeHeight">

                <template #default="{ node, data }">
                    <div class="flex items-center">
                        <el-tag :type="data.menu ? '' : 'info'" size="small" class="mr-2">
                            {{ data.menu ? '菜单' : '权限' }}
                        </el-tag>
                        <span>{{ data.name }}</span>
                    </div>
                </template>

            </el-tree-v2>
        </FormDrawer>

    </el-card>
</template>
<script setup>

import { ref } from 'vue';

import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';

import { Toast } from '~/composables/until.js'

import { getRuleList } from '~/api/rule.js'
//  setRoleRules
import { getRoleList, createRole, updateRole, deleteRole, changeRoleStatus, setRoleRules } from '~/api/role.js'

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
    getList: getRoleList,
    delete: deleteRole,
    changeStatus: changeRoleStatus
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
            desc: '',
            status: 1,
        },
        rules: {
            name: [
                { required: true, message: '角色名称不能为空', trigger: 'blur' },
            ],
            desc: [
                { required: true, message: '角色描述不能为空', trigger: 'blur' },
            ],
        },
        getData,
        update: updateRole,
        create: createRole,

    });


const setRuleFormDrawer = ref(null);

const ruleList = ref([]);
const treeHeight = ref(0);
const roleId = ref(0);
const defaultExpandedKeys = ref([]);
const elTreeRef = ref(null);
const ruleIds = ref([]);
const checkStrictly = ref(false);

const openSetRule = (row) => {
    // console.log(row)
    roleId.value = row.id;
    treeHeight.value = window.innerHeight - 180;
    checkStrictly.value = true;
    getRuleList(1).then(res => {
        // console.log(res);
        ruleList.value = res.list;
        //  默认展开的菜单
        defaultExpandedKeys.value = res.list.map(o => o.id);
        setRuleFormDrawer.value.open();
        //  获取当前角色拥有的权限
        ruleIds.value = row.rules.map(o => o.id);
        setTimeout(() => {
            elTreeRef.value.setCheckedKeys(ruleIds.value)

            checkStrictly.value = false;
        }, 150)
    })
}


const handleChange = (...e) => {
    // console.log(e[1]);
    const { checkedKeys, halfCheckedKeys } = e[1];
    ruleIds.value = [...checkedKeys, ...halfCheckedKeys];
    // console.log(ruleIds.value);
}

// 权限配置
const onSetRuleSubmit = () => {
    setRuleFormDrawer.value.showLoading();
    // 请求接口配置权限
    setRoleRules(roleId.value, ruleIds.value).then(res => {
        // console.log(res);
        Toast('配置成功');
        getData();
        setRuleFormDrawer.value.close();
    }).finally(() => {
        setRuleFormDrawer.value.hideLoading();
    })
}

</script>