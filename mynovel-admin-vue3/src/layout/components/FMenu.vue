<template>
    <div class="f-menu" :style="{ width: $store.state.asideWidth }">
        <el-menu :collapse="isCollapse" :collapse-transition="false" unique-opened :default-active="defaultActive"
            class="border-0" @select="onSelect">
            <template v-for="(item, index) in asideMenus" :key="index">
                <el-sub-menu :index="item.name" v-if="item.child && item.child.length > 0">
                    <template #title>
                        <el-icon>
                            <component :is="item.icon"></component>
                        </el-icon>
                        <span>{{ item.name }}</span>
                    </template>
                    <el-menu-item :index="item2.frontpath" v-for="(item2, index2) in item.child" :key="index2">
                        <el-icon>
                            <component :is="item2.icon"></component>
                        </el-icon>
                        <span>{{ item2.name }}</span>
                    </el-menu-item>
                </el-sub-menu>
                <el-menu-item v-else :index="item.frontpath">
                    <el-icon>
                        <component :is="item.icon"></component>
                    </el-icon>
                    <span>{{ item.name }}</span>
                </el-menu-item>
            </template>
        </el-menu>
    </div>
</template>
<script setup>
import { ref, computed, } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate } from 'vue-router'
import { useStore } from 'vuex'

const router = useRouter();
const store = useStore();
const route = useRoute();
const defaultActive = ref(route.path)

// 监听路由变化 菜单导航联动
onBeforeRouteUpdate((to, from) => {
    // console.log(to)
    defaultActive.value = to.path
})

// 是否折叠
const isCollapse = computed(() => {
    return store.state.asideWidth == '200px' ? false : true;
});

const asideMenus = computed(() => store.state.menus)


// console.log(asideMenus)


const onSelect = (e) => {
    // console.log(e);
    router.push(e);
}



</script>

<style scoped>
.f-menu {
    @apply shadow-md fixed;
    width: 200px;
    top: 64px;
    bottom: 0;
    left: 0;
    overflow-y: auto;
    overflow-x: hidden;
    transition: all 0.2s;
    background-color: white;
}

.f-menu::-webkit-scrollbar {
    width: 0;
}
</style>