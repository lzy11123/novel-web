<template>
    <el-card shadow="never" class="border-0" v-permission="['getRuleList']">
        <ListHeader @create="onCreate" :permissionCreate="'createRule'"  @refresh="onRefresh"></ListHeader>
        <el-tree :data="tableData" :props="{ label: 'name', children: 'child' }" node-key="id"
            :default-expanded-keys="defaultExpandedKeys" v-loading="loading">

            <template #default="{ node, data }">
                <div class="custom-tree-node">
                    <el-tag :type="data.menu ? '' : 'info'">
                        {{ data.menu ? '菜单' : '权限' }}
                    </el-tag>
                    <el-icon v-if="data.icon" :size="16" class="ml-2">
                        <component :is='data.icon'></component>
                    </el-icon>
                    <span class="ml-1">{{ data.name }}</span>

                    <div class="ml-auto" @click.stop="() => { }">
                        <el-switch :modelValue="data.status" :active-value="1" :inactive-value="0"
                            @change="onStatusChange($event, data)"  v-permission="['updateRuleStatus']" />
                        <el-button text type="primary" size="small" @click.stop="onEdit(data)" v-permission="['updateRule']">修改</el-button>
                        <el-button text type="primary" size="small" @click.stop="addChild(data.id)" v-permission="['createRule']">增加</el-button>
                        <el-popconfirm title="是否要删除该菜单权限?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(data.id)">
                            <template #reference>
                                <el-button :disabled="(data.child.length != 0)" text type="primary"
                                    size="small" v-permission="['deleteRule']">删除</el-button>
                            </template>
                        </el-popconfirm>
                    </div>

                </div>
            </template>

        </el-tree>


        <FormDrawer ref="formDrawer" :title="drawerTitle" @submit="onSubmit">
            <el-form ref="formRef" :model="form" :rules="rules" label-width="100px">
                <el-form-item label="上级菜单" prop="rule_id">
                    <el-cascader v-model="form.rule_id" :options="options" :props="{
                        label: 'name', children: 'child',
                        value: 'id',
                        checkStrictly: true,
                        emitPath: false,
                    }" placeholder="请选择上级菜单" />
                </el-form-item>
                <el-form-item label="菜单/规则" prop="menu">
                    <el-radio-group v-model="form.menu">
                        <el-radio :label="1" border>菜单</el-radio>
                        <el-radio :label="0" border>规则</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="form.name" placeholder="请输入名称" />
                </el-form-item>
                <el-form-item label="菜单图标" prop="icon" v-if="form.menu == 1">
                    <IconSelect v-model="form.icon"></IconSelect>
                </el-form-item>
                <el-form-item label="前端路由" prop="frontpath" v-if="form.menu == 1 && form.rule_id > 0">
                    <el-input v-model="form.frontpath" placeholder="请输入前端路由" />
                </el-form-item>
                <el-form-item label="后端规则" prop="condition" v-if="form.menu == 0">
                    <el-input v-model="form.condition" placeholder="请输入后端规则" />
                </el-form-item>
                <el-form-item label="请求方式" prop="method" v-if="form.menu == 0">
                    <el-select v-model="form.method" placeholder="请选择所属角色">
                        <el-option v-for="item in ['GET', 'POST', 'PUT', 'DELETE']" :key="item" :label="item"
                            :value="item" />
                    </el-select>
                </el-form-item>
                <el-form-item label="排序" prop="order">
                    <el-input-number v-model="form.order" :min="0" :max="1000" />
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
import IconSelect from '~/components/IconSelect.vue';

import { getRuleList, createRule, updateRule, deleteRule, changeRuleStatus } from '~/api/rule.js'

import { useInitTable, useInitForm, } from '~/composables/useCommon.js'


const defaultExpandedKeys = ref([]);

const options = ref([]);

const {
    loading,
    searchForm,
    onResetSearchForm,
    onRefresh,
    tableData,
    currentPage,
    total,
    limit,
    getData,
    onChangePage,
    onDelete,
    onStatusChange
} = useInitTable(
    {
        getList: getRuleList,
        onGetListSuccess: (res) => {
            // console.log(res);
            tableData.value = res.list;
            options.value = res.rules;
            defaultExpandedKeys.value = res.list.map(o => o.id)
        },
        delete: deleteRule,
        changeStatus: changeRuleStatus
    }
);


const {
    formDrawer,
    formRef,
    form,
    editId,
    drawerTitle,
    rules,
    onSubmit,
    onResetForm,
    onCreate,
    onEdit
} = useInitForm({
    form: {
        rule_id: 0,
        menu: 0,
        name: '',
        condition: '',
        method: 'GET',
        status: 1,
        order: 50,
        icon: '',
        frontpath: ''
    },
    rules: {
        name: [
            { required: true, message: '名称不能为空', trigger: 'blur' },
        ],
        // password: [
        //     { required: true, message: '密码不能为空', trigger: 'blur' },
        // ],
    },
    getData,
    update: updateRule,
    create: createRule,
});

// 添加子分类
const addChild = (id) => {
    onCreate();
    form.rule_id = id;
    form.status = 1;
}
</script>

<style scoped>
.custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    font-size: 14px;
    padding-right: 8px;
}

:deep(.el-tree-node__label) {
    width: 100%;
}

:deep(.el-tree-node__content) {
    padding: 20px 0;
}
</style>