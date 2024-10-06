<template>
    <div>
        <div class="f-tag-list" :style="{ left: $store.state.asideWidth }">
            <el-tabs v-model="activeTab" type="card" @tab-remove="removeTab" @tab-change="changeTab"
                style="min-width:0;margin-right: 5px;">
                <el-tab-pane v-for="item in tabList" :closable="item.path != '/'" :key="item.path" :label="item.title"
                    :name="item.path">
                </el-tab-pane>

            </el-tabs>
            <span class="tag-btn">
                <el-dropdown @command="onCloseTab">
                    <span>
                        <el-icon>
                            <arrow-down />
                        </el-icon>
                    </span>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item command="clearOther">关闭其他</el-dropdown-item>
                            <el-dropdown-item command="clearAll">关闭全部</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </span>
        </div>
        <div style="height:44px"></div>
    </div>

</template>


<script  setup>
import { useTabList } from '~/composables/useTabList.js'
const { activeTab,
    tabList,
    changeTab,
    removeTab,
    onCloseTab } = useTabList();
</script>
<style scoped>
.f-tag-list {
    @apply fixed bg-gray-100 flex items-center px-2;
    top: 64px;
    right: 0;
    height: 44px;
    z-index: 100;
}

.tag-btn {
    @apply bg-white rounded ml-auto flex items-center justify-center;
    width: 32px;
    height: 32px;
}

:deep(.el-tabs__header) {
    @apply mb-0;
    height: 32px;
    border: 0;
}

:deep(.el-tabs__nav) {
    border: 0 !important;
}

:deep(.el-tabs__item) {
    @apply bg-white mx-1 rounded;
    border: 0 !important;
    height: 32px;
    line-height: 32px;

}

:deep(.el-tabs__nav-next),
:deep(.el-tabs__nav-prev) {
    line-height: 38px;
}

:deep(.is-disabled) {
    cursor: no-drop;
    color: #c0c4cc;
}
</style>