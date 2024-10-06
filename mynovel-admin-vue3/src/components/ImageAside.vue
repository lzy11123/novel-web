<template>
    <el-aside width="220px" class="image-aside" v-loading="loading">
        <div class="top">
            <AsideList v-for="(item, index) in list" :key="index" :active="activeId == item.id"
                @edit="onEditDrawer(item)" @delete="onDelete(item.id)" @click="onChangeActiveId(item.id)">
                {{ item.name }}
            </AsideList>
        </div>
        <div class="bottom">
            <el-pagination background layout="prev, next" :total="total" :current-page="currentPage" :page-size="limit"
                @current-change="onChangePage" />
        </div>


        <FormDrawer ref="formDrawerRef" :title="editTitle" @submit="onSubmit">
            <el-form :model="form" :rules="rules" ref="formRef" label-width="80px">
                <el-form-item label="分类名称" prop="name">
                    <el-input v-model="form.name" />
                </el-form-item>
                <el-form-item label="排序">
                    <el-input-number v-model="form.order" :min="0" :max="1000" />
                </el-form-item>
            </el-form>
        </FormDrawer>

    </el-aside>

</template>
<script setup>
import { computed, reactive, ref } from 'vue'

import AsideList from './AsideList.vue';
import FormDrawer from './FormDrawer.vue';

import { getImageClassList, createImageClass, editImageClass, deleteImageClass } from '~/api/image_class.js'
import { Toast } from '~/composables/until.js'

// 加载动画
const loading = ref(false)

const list = ref([]);
const activeId = ref(0)
// 分页
const currentPage = ref(1);
const total = ref(0);
const limit = ref(15);


//获取数据
function getData(p = false) {

    if (typeof (p) == 'number') {
        currentPage.value = p;
    }

    loading.value = true;

    getImageClassList(currentPage.value, limit.value).then(res => {
        // console.log(res);
        list.value = res.list;
        total.value = res.totalCount;
        let item = list.value[0]
        if (item) {
            // 获取第一个id
            onChangeActiveId(item.id)
        }
    }).finally(() => {
        loading.value = false;
    })
}

// 翻页
const onChangePage = (page) => {
    // console.log(page)
    getData(page);
}

// 初始化数据
getData(1);

// 表单新增
const formDrawerRef = ref(null);
const formRef = ref(null);
const editId = ref(0);
const editTitle = computed(() => {
    return editId == 0 ? '新增' : '修改'
})
const rules = reactive({
    name: [
        { required: true, message: '分类名称不能为空', trigger: 'blur' },
    ],
})
const form = reactive({
    name: '',
    order: 50
})

// 重置表单
const resetForm = (row) => {
    if (formRef.value) formRef.value.clearValidate();

    if (row) {
        for (const key in form) {
            form[key] = row[key];
        }
    }

}


// 新增分类名称 打开弹框
const onOpenDrawer = () => {
    resetForm({
        name: '',
        order: 50,
    })
    editId.value = 0;
    formDrawerRef.value.open()
};

// 修改分类名称 打开弹框
const onEditDrawer = (row) => {
    // console.log(row);
    resetForm(row);
    editId.value = row.id;
    formDrawerRef.value.open()
}

// 新增修改数据提交
const onSubmit = () => {
    formRef.value.validate((valid) => {
        if (!valid) return;

        formDrawerRef.value.showLoading();

        const fun = editId.value != 0 ? editImageClass(editId.value, form) : createImageClass(form)

        fun.then(res => {
            // console.log(res);
            Toast(editTitle.value + '成功');
            getData(editId.value ? currentPage.value : 1);
            formDrawerRef.value.close();
        }).finally(() => {
            formDrawerRef.value.hideLoading();
        })

    })
}
// 删除图库分类
const onDelete = (id) => {
    loading.value = true;
    deleteImageClass(id).then(res => {

        Toast('删除成功');
        getData();

    }).finally(() => {
        loading.value = false;
    })
}


// 选中图片分类id


const emit = defineEmits(['change'])

// 切换分类
function onChangeActiveId(id) {
    activeId.value = id;
    emit('change', id)
}




defineExpose({
    onOpenDrawer,
})

</script>
<style scoped>
.image-aside {
    border-right: 1px solid #eee;
    position: relative;
}

.image-aside .top {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 50px;
    overflow-y: auto;
}

.image-aside .bottom {
    @apply flex items-center justify-center;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
}

.aside-list {
    border-bottom: 1px solid #f4f4f4;
    @apply flex items-center p-3 text-sm text-gray-600 cursor-pointer;
}

.aside-list:hover {
    @apply bg-blue-50;
}

.aside-list.active {
    @apply bg-blue-50;
}
</style>