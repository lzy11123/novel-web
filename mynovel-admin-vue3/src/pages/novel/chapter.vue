<template>
    <el-card shadow="never" v-loading="loading" v-permission="['getNovelList']">

        <!-- 刷新|新增 -->
        <ListHeader @create="onCreate" :permissionCreate="'createNovel'" @refresh="onRefresh"></ListHeader>

        <el-table :data="list" stripe style="width: 100%">
            <el-table-column label="章节名称">
                <template #default="scope">
                    <div class="flex items-center">
                        <div>
                            <h6>{{ scope.row.name }}</h6>
                        </div>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="序号" prop="order"></el-table-column>
            <el-table-column label="发布状态" width="120" v-permission="['updateNovelStatus']">
                <template #default="{ row }">
                    <el-switch :modelValue="row.status" :active-value="1" :inactive-value="0"
                        @change="onStatusChange($event, row)" :disabled="row.super == 1" />
                </template>
            </el-table-column>
            <el-table-column label="发布时间" width="300">
                <template #default="{ row }">
                    <div class="text-xs">近期修改时间：{{ row.update_time }}</div>
                    <div class="text-xs text-gray-400 mt-2">发布时间：{{ row.create_time }}</div>
                </template>
            </el-table-column>
            <el-table-column label="操作" align="center" width="200">
                <template #default="scope">
                    <span v-if="scope.row.super == 1">暂无操作</span>
                    <div v-else>
                        <el-button text type="primary" size="small" @click="onEdit(scope.row)"
                            v-permission="['updateNovel']">修改</el-button>
                        <el-popconfirm title="是否要删除该章节?" confirm-button-text="确定" cancel-button-text="取消"
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
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="排序" prop="order">
                    <el-input-number v-model="form.order" :min="0" />
                </el-form-item>
                <el-form-item label="详情介绍" prop="email">
                    <Editor v-model="form.content"></Editor>
                </el-form-item>

            </el-form>
        </FormDrawer>


    </el-card>
</template>
<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router'
import ListHeader from '~/components/ListHeader.vue';
import FormDrawer from '~/components/FormDrawer.vue';
import Editor from '~/components/Editor.vue';
import { Toast } from '~/composables/until';
import { getNovelChapterList, createNovelChapter, updateNovelChapter, changeNovelChapterStatus, deleteNovelChapter } from '~/api/novel_chapter.js'


import { useInitForm } from '~/composables/useCommon.js'

const currentPage = ref(1);
const limit = ref(20);
const total = ref(0)

const list = ref([]);
const loading = ref(false);
const novel_id = ref(1)

const route = useRoute();

// console.log(route.params.id)
novel_id.value = route.params.id

function getData(p = null) {
    if (typeof p == 'number') {
        currentPage.value = p;
    }

    loading.value = true;

    getNovelChapterList(novel_id.value, currentPage.value, limit.value).then(res => {
        // console.log(res);
        total.value = res.totalCount;
        list.value = res.list.map(item => {
            item.checked = false;
            return item;
        });
    }).finally(() => {
        loading.value = false;
    })
}

// 切换分页
const onChangePage = (page) => {
    getData(page)
}

// 删除
const onDelete = (id) => {
    // console.log(id)
    loading.value = true;
    deleteNovelChapter(id).then(res => {
        Toast('删除成功');
        getData();
    }).finally(() => {
        loading.value = false;
    })
}


// 修改状态
const onStatusChange = (status, row) => {
    // console.log(status)
    // row.statusLoading = true;
    loading.value = true
    changeNovelChapterStatus(row.id, status).then(res => {
        row.status = status;
        Toast('状态修改成功');
    }).finally(() => {
        // row.statusLoading = false;
        loading.value = false
    })
}

getData();
// 刷新
const onRefresh = () => {
    getData()
}
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
            content: "",
            status: 1,
            novel_id: null,
            order: 0,
        },
        rules: {
            name: [
                { required: true, message: '章节名称不能为空', trigger: 'blur' },
            ],
        },
        getData,
        update: updateNovelChapter,
        create: createNovelChapter,
        beforeSubmit(data) {
            let body = { ...data };
            body.novel_id = novel_id.value;

            return body;
        },
    });


</script>