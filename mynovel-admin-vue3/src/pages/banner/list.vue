<template>
  <el-card shadow="never" v-loading="loading" v-permission="['getBannerList']">
    <!-- 刷新|新增 -->
    <!-- :permissionCreate="'createSkus'" :permissionDelete="'deleteAllSkus'" -->
    <ListHeader
      :permissionCreate="'createBanner'"
      layout="create,refresh"
      @create="onCreate"
      @refresh="onRefresh"
    >
    </ListHeader>

    <el-table
      ref="multipleTableRef"
      :data="tableData"
      stripe
      style="width: 100%"
    >
      <el-table-column prop="title" label="标题" />
      <el-table-column prop="default" label="图片" width="150px">
        <template #default="{ row }">
          <el-image
            :src="row.cover"
            style="width: 100px; height: auto"
          ></el-image>
        </template>
      </el-table-column>
      <el-table-column label="状态" width="120" align="center">
        <template #default="{ row }">
          <el-switch
            :modelValue="row.status"
            :active-value="1"
            :inactive-value="0"
            v-permission="['updateBannerStatus']"
            @change="onStatusChange($event, row)"
          />
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="300">
        <template #default="scope">
          <!-- v-permission="['updateSkus']" -->
          <el-button
            text
            type="primary"
            size="small"
            @click="onEdit(scope.row)"
            v-permission="['updateBanner']"
            >修改</el-button
          >
          <el-popconfirm
            title="是否要删除该banner?"
            confirm-button-text="确定"
            cancel-button-text="取消"
            @confirm="onDelete(scope.row.id)"
          >
            <template #reference>
              <el-button
                text
                type="primary"
                size="small"
                v-permission="['deleteBanner']"
                >删除</el-button
              >
            </template>
          </el-popconfirm>
        </template>
      </el-table-column>
    </el-table>
    <div class="flex items-center justify-center mt-5">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        :current-page="currentPage"
        :page-size="limit"
        @current-change="onChangePage"
      />
    </div>
    <FormDrawer
      ref="formDrawer"
      :title="drawerTitle"
      @submit="onSubmit"
      destroyOnClose
    >
      <el-form ref="formRef" :model="form" :rules="rules" label-width="80px">
        <el-form-item label="标题" prop="title">
          <el-input v-model="form.title" placeholder="请输入标题" />
        </el-form-item>
        <el-form-item label="封面" prop="cover">
          <ChooseImage v-model="form.cover"></ChooseImage>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-switch
            v-model="form.status"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
        <el-form-item label="页面链接" prop="status">
          <el-input v-model="form.url" placeholder="请输入链接" />
        </el-form-item>
      </el-form>
    </FormDrawer>
  </el-card>
</template>
<script setup>
import ListHeader from "~/components/ListHeader.vue";
import FormDrawer from "~/components/FormDrawer.vue";

import ChooseImage from "~/components/ChooseImage.vue";

import {
  getBannerList,
  createBanner,
  updateBanner,
  changeBannerStatus,
  deleteBanner,
} from "~/api/banner.js";

import { useInitTable, useInitForm } from "~/composables/useCommon.js";

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
  onStatusChange,
} = useInitTable({
  getList: getBannerList,
  delete: deleteBanner,
  changeStatus: changeBannerStatus,
});

const {
  formDrawer,
  formRef,
  form,
  drawerTitle,
  rules,
  onSubmit,
  onCreate,
  onEdit,
} = useInitForm({
  form: {
    title: "",
    cover: "",
    status: 1,
    url: "",
  },
  rules: {
    title: [{ required: true, message: "标题不能为空", trigger: "blur" }],
  },
  getData,
  update: updateBanner,
  create: createBanner,
});
</script>