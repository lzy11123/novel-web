<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getNovelList']">

        <!-- 搜索 -->
        <Search :model="searchForm" @search="getData" @reset="onResetSearchForm">
            <SearchItem label="关键词">
                <el-input v-model="searchForm.keyword" placeholder="名称/标签" clearable />
            </SearchItem>
            <template #show>
                <SearchItem label="所属分类">
                    <el-select v-model="searchForm.catagory_id" placeholder="请选择会员等级">
                        <el-option v-for="item in category" :key="item.id" :label="item.name" :value="item.id" />
                    </el-select>
                </SearchItem>
            </template>
        </Search>

        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createNovel'" @refresh="onRefresh"></ListHeader>

        <el-table :data="tableData" stripe style="width: 100%">
            <el-table-column label="封面" width="150">
                <template #default="scope">
                    <el-image style="width: 120px; height: 180px" :src="scope.row.cover" fit="cover" />

                </template>
            </el-table-column>
            <el-table-column label="名称">
                <template #default="scope">
                    <div class="flex items-center">
                        <div>
                            <h6>{{ scope.row.name }}</h6>
                            <small>ID: {{ scope.row.id }}</small>
                        </div>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="所属分类">
                <template #default="{ row }">
                    <el-tag type="warning" effect="dark">
                        {{ row.category?.name || '-' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="所属标签">
                <template #default="{ row }">
                    <div v-if="row.tag">
                        <el-tag v-for="tag in row.tag" :key="tag" class="m-1">
                            {{ tag }}
                        </el-tag>
                    </div>
                    <div v-else>
                        -
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="发布状态" width="120" v-permission="['updateNovelStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" :disabled="row.super == 1" />
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="order"></el-table-column>
            <el-table-column label="发布时间" width="300">
                <template #default="{ row }">
                    <div class="text-xs">近期修改时间：{{ row.update_time }}</div>
                    <div class="text-xs text-gray-400 mt-2">发布时间：{{ row.create_time }}</div>
                </template>
            </el-table-column>
            <el-table-column label="操作" align="center">
                <template #default="scope">
                    <span v-if="scope.row.super == 1">暂无操作</span>
                    <div v-else>
                        <el-button text type="primary" size="small" @click="onEdit(scope.row)"
                            v-permission="['updateNovel']">修改</el-button>
                        <el-button text type="primary" size="small" @click="toChapter(scope.row)"
                            v-permission="['updateNovel']">章节列表</el-button>
                        <el-popconfirm title="是否要删除该用户?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDelete(scope.row.id)">
                            <template #reference>
                                <el-button text type="primary" size="small" v-permission="['deleteNovel']">删除</el-button>
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
                <el-form-item label="名称" prop="name">
                    <el-input v-model="form.name" placeholder="请输入名称" />
                </el-form-item>

                <el-form-item label="封面" prop="cover">
                    <ChooseImage v-model="form.cover"></ChooseImage>
                </el-form-item>
                <el-form-item label="所属分类" prop="category_id">
                    <el-select v-model="form.category_id" placeholder="请选择所属分类">
                        <el-option v-for="item in category" :key="item.id" :label="item.name" :value="item.id" />
                    </el-select>
                </el-form-item>
                <el-form-item label="标签" prop="tag">
                    <el-tag v-for="tag in form.tag" :key="tag" class="m-1" closable :disable-transitions="false"
                        @close="handleDeleteTag(tag)">
                        {{ tag }}
                    </el-tag>
                    <el-button class="button-new-tag ml-1" size="small" @click="handleOpenTag">
                        + 添加标签
                    </el-button>
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="排序" prop="order">
                    <el-input-number v-model="form.order" :min="0" />
                </el-form-item>

                <el-form-item label="详情介绍" prop="email">
                    <el-input type="textarea" v-model="form.desc" rows="8"></el-input>
                </el-form-item>

            </el-form>
        </FormDrawer>

        <el-dialog v-model="dialogVisible" title="标签" width="50%">
            <div>
                <el-checkbox-group v-model="chooseTagList">
                    <el-checkbox border class="mb-2" v-for="item in tagList" :key="item" :label="item" />
                </el-checkbox-group>
            </div>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogVisible = false">取消</el-button>
                    <el-button type="primary" @click="onSubmitTag">
                        确定
                    </el-button>
                </span>
            </template>
        </el-dialog>
    </el-card>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router'

import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';
import ChooseImage from '~/components/ChooseImage.vue';
import Search from '~/components/Search.vue';
import SearchItem from '~/components/SearchItem.vue';

import { getNovelList, createNovel, updateNovel, deleteNovel, changeNovelStatus, getNovelTagList } from '~/api/novel.js'

import { useInitTable, useInitForm } from '~/composables/useCommon.js'

const category = ref([]);

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
        category_id: '',
    },
    getList: getNovelList,
    onGetListSuccess: (res) => {
        tableData.value = res.list.map(item => {
            item.tag = item.tag.split(",");
            return item;
        });
        total.value = res.totalCount;
        category.value = res.category
    },
    delete: deleteNovel,
    changeStatus: changeNovelStatus
});




const {
    formDrawer,
    formRef,
    form,
    drawerTitle,
    rules,
    onSubmit,
    onCreate,
    editId,
    onEdit } = useInitForm({
        form: {
            name: "",
            desc: "",
            status: 1,
            tag: '',
            category_id: null,
            cover: '',
            order: 0,
        },
        rules: {
            name: [
                { required: true, message: '名称不能为空', trigger: 'blur' },
            ],
            category_id: [
                { required: true, message: '所属分类不能为空', trigger: 'blur' },
            ],
        },
        getData,
        update: updateNovel,
        create: createNovel,
        beforeSubmit: (data) => {

            let body = { ...data };
            body.tag = body.tag.toString();

            return body;
        }
    });


const tagList = ref([]);
const chooseTagList = ref([]);
const dialogVisible = ref(false)

const handleOpenTag = () => {

    onGetNovelTagList().then(res => {
        dialogVisible.value = true;
        chooseTagList.value = form.tag || [];
    });
}
const handleCloseTag = () => {

    dialogVisible.value = false;
}

const handleDeleteTag = (tag) => {
    let index = form.tag.findIndex(item => tag == item);
    form.tag.splice(index, 1);
}

const onGetNovelTagList = () => {
    return getNovelTagList().then(res => {
        // console.log(res);
        tagList.value = res.list.map(item => item.name);

    })
}

const onSubmitTag = () => {
    form.tag = chooseTagList.value;
    handleCloseTag();

}


const router = useRouter();


const toChapter = (item) => {
    router.push({
        path: '/novel/chapter/' + item.id,
    })
}

</script>