<template>
    <el-main class="image-main" v-loading="loading">
        <div class="top p-3">
            <el-row :gutter="10">
                <el-col :span="6" :offet="0" v-for="(item, index) in list" :key="index">
                    <el-card shadow="hover" class="relative mb-2" :class="{ 'border-blue-500': item.checked }"
                        :body-style="{ 'padding': '0px' }">
                        <el-image fit="cover" :src="item.url" class="w-full h-[150px]" :preview-src-list="[item.url]"
                            :initial-index="0"></el-image>
                        <div class="image-title">
                            {{ item.name }}
                        </div>
                        <div class="flex items-center justify-center p-2 flex-wrap">

                            <el-checkbox v-if="openChoose" v-model="item.checked" @change="onChange(item)" />

                            <el-button text type="primary" size="small" @click="onEditName(item)" v-permission="['updateImageName']">
                                重命名
                            </el-button>

                            <el-popconfirm title="是否要删除该图片?" confirm-button-text="确定" cancel-button-text="取消"
                                @confirm="onDeleteImage(item)">
                                <template #reference>
                                    <el-button class="!m-0" text type="primary" size="small" v-permission="['deleteImage']">
                                        删除
                                    </el-button>
                                </template>
                            </el-popconfirm>

                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </div>
        <div class="bottom">
            <el-pagination background layout="prev, pager, next" :total="total" :current-page="currentPage"
                :page-size="limit" @current-change="onChangePage" />
        </div>


        <el-drawer v-model="uploadDrawer" title="上传图片">
            <UploadFile :data="{ image_class_id }" @success="onUploadSuccess"></UploadFile>
        </el-drawer>


    </el-main>
</template>
<script setup>

import { computed, ref } from 'vue';

import UploadFile from '~/components/UploadFile.vue'

import { getImageList, updateImageName, deleteImage } from '~/api/image.js'
import { Toast, showPrompt } from '~/composables/until.js'

const props = defineProps({
    openChoose: {
        type: Boolean,
        default: false,
    },
    limit: {
        type: Number,
        default: 1,
    }
})

// 分页
const currentPage = ref(1);
const limit = ref(16);
const total = ref(0)

const list = ref([]);
const loading = ref(false);
const image_class_id = ref(0)


function getData(p = null) {
    if (typeof p == 'number') {
        currentPage.value = p;
    }

    loading.value = true;

    getImageList(image_class_id.value, currentPage.value, limit.value).then(res => {
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

// 根据分类id重新加载图片列表
const loadData = (id) => {
    currentPage.value = 1;
    image_class_id.value = id;
    getData();
}

// 重命名
const onEditName = (item) => {
    showPrompt('重命名', item.name).then(({ value }) => {
        // console.log(value);

        loading.value = true;
        updateImageName(item.id, value).then(res => {

            Toast('修改成功');
            getData();
        }).finally(() => {
            loading.value = false;
        })

    }).catch(err => {

    })
}

// 删除图片
const onDeleteImage = (item) => {
    // console.log(item)
    loading.value = true;
    deleteImage([item.id]).then(res => {
        Toast('删除成功');
        getData();
    }).finally(() => {
        loading.value = false;
    })
}

// 打开上传图片弹框
const uploadDrawer = ref(false)
const onOpenUploadDrawer = () => {
    uploadDrawer.value = true;
}


//上传成功

const onUploadSuccess = () => {
    Toast('上传成功')
    getData();
}


const checkedImage = computed(() => {
    return list.value.filter(o => o.checked)
})


const emit = defineEmits(['choose']);

// 选择图片
const onChange = (item) => {
    if (item.checked && checkedImage.value.length > props.limit) {
        item.checked = false;
        return Toast(`最多只能选中${props.limit}张`, 'error')
    }

    emit('choose', checkedImage.value)
}



defineExpose({
    loadData,
    onOpenUploadDrawer
})

</script>
<style scoped>
.image-main {
    position: relative;
}

.image-main .top {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 50px;
    overflow-y: auto;
}

.image-main .bottom {
    @apply flex items-center justify-center;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
}

.image-title {
    @apply text-sm truncate text-gray-100 bg-opacity-50 bg-gray-800 px-2 py-1;
    position: absolute;
    top: 122px;
    left: -1px;
    right: -1px;


}
</style>