<template>
    <div>
        <el-container>
            <el-header>
                <f-header></f-header>
            </el-header>
            <el-container>
                <el-aside :width="$store.state.asideWidth">
                    <f-menu></f-menu>
                </el-aside>
                <el-main>
                    <f-tag-list></f-tag-list>
                    <router-view v-slot="{ Component }">
                        <transition name="fade">
                            <component :is="Component"></component>
                        </transition>
                    </router-view>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script setup>
import FHeader from './components/FHeader.vue'
import FMenu from './components/FMenu.vue'
import FTagList from './components/FTagList.vue'

</script>

<style scoped>
.el-aside {
    transition: all 0.2s;
}

.fade-enter-from {
    opacity: 0;
}

.fade-enter-to {
    opacity: 1;
}

.fade-leave-from {
    opacity: 1;
}

.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s;
}

.fade-enter-active {
    transition-delay: 0.3s;
}
</style>