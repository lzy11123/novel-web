import { router, addRoutes } from "./router";
import store from "./store";
import { Toast, showFullLoading, hideFullLoading, getToken } from './composables/until.js'


// let hasGetInfo = false;
// 全局前置守卫
router.beforeEach(async (to, from, next) => {


    // 显示loading
    showFullLoading();
    const token = getToken();

    // 没有登录，强制跳转到登录页面
    if (!token && to.path != '/login') {
        Toast('请先登录', 'error');
        return next({
            path: '/login'
        })
    }

    // 已经登录，防止重复登录
    if (token && to.path == '/login') {
        Toast('请勿重新登录', 'error');
        return next({
            path: from.path ? from.path : '/'
        })
    }


    let hasNewRoutes = false;
    // 用户登录了，判断是否有用户信息，没有-> 获取用户信息 存储在vuex中 

    if (token && JSON.stringify(store.state.user) == '{}') {
        // 加载路由
        let { menus } = await store.dispatch('getManagerInfo');
        // 动态添加路由
        hasNewRoutes = addRoutes(menus);
    }

    // 设置页面标题
    let title = (to.meta.title ? to.meta.title + '-' : '') + '后台管理系统'
    document.title = title;


    hasNewRoutes ? next(to.fullPath) : next();

})



// 全局后置守卫
router.afterEach((to, form) => {
    // 隐藏loading

    hideFullLoading();
})