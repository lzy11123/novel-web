<template>
    <div>
        <FormDrawer ref="formDrawerRef" title="推荐产品" size="50%" @submit="onOpenChooseGoods" confirmText="关联">
            <el-table :data="tableData" border stripe style="width:100%">
                <el-table-column prop="goods_id" label="id" width="70" align="center" />
                <el-table-column prop="name" label="商品封面" width="140" align="center">
                    <template #default="{ row }">
                        <el-image :src="row.cover" style="width:100px;height:64px" fit="cover" :lazy="true"></el-image>
                    </template>
                </el-table-column>
                <el-table-column prop="name" label="商品名称">
                </el-table-column>
                <el-table-column label="操作" align="center" width="150">
                    <template #default="{ row }">
                        <el-popconfirm title="是否要删除该关联产品?" confirm-button-text="确定" cancel-button-text="取消"
                            @confirm="onDeleteCategoryGoods(row)">
                            <template #reference>
                                <el-button type="primary" size="small" text :loading="row.loading">
                                    删除
                                </el-button>
                            </template>
                        </el-popconfirm>
                    </template>
                </el-table-column>
            </el-table>

        </FormDrawer>
        <ChooseGoods ref="chooseGoodsRef"></ChooseGoods>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import FormDrawer from '~/components/FormDrawer.vue';
import ChooseGoods from '~/components/ChooseGoods.vue';

import { getCategoryGoods, deleteCategoryGoods, connectCategoryGoods } from '~/api/category.js';
import { Toast } from '~/composables/until.js';

const formDrawerRef = ref(null)

const category_id = ref(0);
const tableData = ref([]);


const open = (item) => {
    category_id.value = item.id;
    item.goodsDrawerLoading = true;
    getData().then(res => {
        formDrawerRef.value.open();
    }).finally(() => {
        item.goodsDrawerLoading = false;
    })
}


const onDeleteCategoryGoods = (item) => {
    item.loading = true;
    deleteCategoryGoods(item.id).then(res => {
        Toast('删除成功')
        getData();
    })
}

function getData() {
    // 获取关联产品数据
    return getCategoryGoods(category_id.value).then(res => {
        // console.log(res);
        tableData.value = res.map(o => {
            o.loading = false;
            return o;
        });
    })

}

const chooseGoodsRef = ref(null);

const onOpenChooseGoods = () => {
    // formDrawerRef.value.showLoading();
    chooseGoodsRef.value.open((goods_ids) => {
        // console.log(goods_ids)
        connectCategoryGoods({
            category_id: category_id.value,
            goods_ids: goods_ids,
        }).then(res => {
            Toast('关联成功')
            getData();
        }).finally(() => {
            // formDrawerRef.value.hideLoading();
        })
    });
}

defineExpose({
    open
})

</script>