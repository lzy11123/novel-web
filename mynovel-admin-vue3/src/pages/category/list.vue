<template>
    <el-card shadow="never" class="border-0" v-permission="['getCategoryList']">
        <ListHeader @create="onCreate" :permissionCreate="'createCategory'" @refresh="onRefresh"></ListHeader>
        <el-tree :data="tableData" :props="{ label: 'name', children: 'child' }" node-key="id" v-loading="loading"
            default-expand-all draggable @node-drag-end="handleDragEnd" @node-drop="handleDrop">

            <template #default="{ node, data }">
                <div class="custom-tree-node">
                    <span class="ml-1">{{ data.name }}</span>
                    <div class="ml-auto" @click.stop="() => { }">
                        <el-switch class="mr-2" :modelValue="data.status" :active-value="1" :inactive-value="0"
                            @change="onStatusChange($event, data)" v-permission="['updateCategoryStatus']" />

                        <el-button text type="primary" size="small" @click.stop="addChild(data.id)"
                            v-permission="['createCategory']">新增</el-button>

                        <el-button text type="primary" size="small" @click.stop="onEdit(data)"
                            v-permission="['updateCategory']">修改</el-button>

                        <el-popconfirm title="是否要删除该分类?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(data.id)">
                            <template #reference>
                                <el-button :disabled="(data.child.length != 0)" text type="primary" size="small"
                                    v-permission="['deleteCategory']">删除</el-button>
                            </template>
                        </el-popconfirm>
                    </div>

                </div>
            </template>

        </el-tree>


        <FormDrawer ref="formDrawer" :title="drawerTitle" @submit="onSubmit" size="50%">
            <el-form ref="formRef" :model="form" :rules="rules" label-width="100px">

                <el-form-item label="分类名称" prop="name">
                    <el-input v-model="form.name" placeholder="请输入名称" />
                </el-form-item>
                <el-form-item label="上级分类" prop="category_id">
                    <el-cascader v-model="form.category_id" :options="options" :props="{
                        label: 'name', children: 'child',
                        value: 'id',
                        checkStrictly: true,
                        emitPath: false,
                    }" placeholder="请选择上级分类" class="w-100" />
                </el-form-item>

                <el-form-item label="分类图片" prop="category_img">
                    <ChooseImage v-model="form.category_img"></ChooseImage>
                </el-form-item>

                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="排序" prop="order">
                    <el-input-number v-model="form.order" :min="0" :max="10000" />
                </el-form-item>


            </el-form>
        </FormDrawer>

    </el-card>
</template>
<script setup>

import { ref, computed } from 'vue';

import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';

import ChooseImage from '~/components/ChooseImage.vue';

import Editor from '~/components/Editor.vue';


import { getCategoryList, createCategory, updateCategory, deleteCategory, changeCategoryStatus, sortCategory } from '~/api/category.js'

import { useInitTable, useInitForm, } from '~/composables/useCommon.js'

import { Toast } from '~/composables/until'

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
        getList: getCategoryList,
        onGetListSuccess: (res) => {
            // console.log(res);
            tableData.value = res.map(o => {
                o.goodsDrawerLoading = false;
                return o;
            });
            options.value = res.map(o => {
                return o;
            });;
        },
        delete: deleteCategory,
        changeStatus: changeCategoryStatus
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
        category_id: 0,
        name: '',
        category_img: '',
        status: 1,
        order: 50,

    },
    rules: {
        name: [
            { required: true, message: '分类名称不能', trigger: 'blur' },
        ],
        // password: [
        //     { required: true, message: '密码不能为空', trigger: 'blur' },
        // ],
    },
    getData,
    update: updateCategory,
    create: createCategory,
});


// 添加子分类
const addChild = (id) => {
    onCreate();
    form.category_id = id;
    form.status = 1;
}

const handleDragEnd = (
    draggingNode,
    dropNode,
    dropType,
    ev,
) => {
    // draggingNode,
    // dropNode,

    let moveItem = draggingNode;
    let sideItem = dropNode

    // console.log(moveItem)
    // console.log(sideItem)

    if (sideItem) {
        // 如果拖动后在分类的上下 => sideItem的category_id   如果是在分类的里面 => sideItem的id
        if (dropType == 'before' || dropType == 'after') {
            moveItem.data.category_id = sideItem.data.category_id;
        } else {
            moveItem.data.category_id = sideItem.data.id;
        }
        // console.log(sideItem.data);
    }



}
const handleDrop = (
    draggingNode,
    dropNode,
    dropType,
    ev,
) => {
    // console.log(dropNode);
    // console.log('tree drop:', dropNode.label, dropType)

    // console.log(sortdata.value)

    sortCategory(JSON.stringify(sortdata.value)).then(res => {
        // console.log(res);
        Toast('排序成功');
    })



}

const sortdata = computed(() => {
    let data = [];
    let sort = function (arr) {
        arr.forEach((item) => {
            data.push(item);
            if (item.child.length) {
                sort(item.child);
            }
        });
    };

    sort(tableData.value);

    data = data.map((item, index) => {
        return {
            id: item.id,
            order: index,
            category_id: item.category_id,
        };
    });
    // console.log(data);
    return data;
},)





</script>

<style scoped>
.custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    font-size: 14px;
    padding-right: 8px;
}

:deep(.el-tree-node__content) {
    padding: 20px 0;
}

:deep(.el-tree-node__label) {
    width: 100%;
}
</style>