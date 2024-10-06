import { ref } from 'vue'
import { useRoute, onBeforeRouteUpdate, useRouter } from 'vue-router'

export function useTabList() {

    const route = useRoute();
    const router = useRouter();

    const activeTab = ref(route.path);
    const tabList = ref([
        {
            title: '主控台',
            path: '/',
        },
    ])


    // 初始化标签导航列表
    const initTabList = () => {

        let tabs = localStorage.getItem('tabList');

        if (tabs) {
            tabList.value = tabs ? JSON.parse(tabs) : [];
        }
    }
    initTabList();
    // 路由
    onBeforeRouteUpdate((to, from) => {
        // console.log(to);
        activeTab.value = to.path;
        addTab({
            title: to.meta.title,
            path: to.path
        })
    })
    //添加标签导航
    const addTab = (tab) => {
        let noTab = tabList.value.findIndex(t => t.path == tab.path);
        // console.log(noTab)
        if (noTab == -1) {
            tabList.value.push(tab);
        }
        // 存储到cookie中
        localStorage.setItem('tabList', JSON.stringify(tabList.value));

    }
    // 改变标签导航
    const changeTab = (t) => {
        // console.log(t);
        activeTab.value = t;
        router.push(t);
    }
    // 移除标签导航
    const removeTab = (t) => {
        // console.log(t);
        let active = activeTab.value;
        let tabs = tabList.value;

        // 判断关闭导航是否当前导航 
        if (active == t) {
            tabs.forEach((tab, index) => {
                // 是当前导航 查看当前导航是否有下一个导航或者上一个导航，作为关闭后前往导航
                if (tab.path == t) {
                    let nextTab = tabs[index + 1] || tabs[index - 1];
                    if (nextTab) {
                        active = nextTab.path;
                    }
                }
            })
        }

        activeTab.value = active;
        tabList.value = tabs.filter(item => item.path != t);
        localStorage.setItem('tabList', JSON.stringify(tabList.value));

    }
    const onCloseTab = (c) => {
        // console.log(c)
        switch (c) {
            case 'clearOther':
                // 过滤只剩下首页和当前页面
                tabList.value = tabList.value.filter(item => item.path == activeTab.value || item.path == '/');
                break;
            case 'clearAll':
                //切换回首页
                activeTab.value = '/';
                // 过滤只留下首页
                tabList.value = [{
                    title: '主控台',
                    path: '/'
                }]
                break;
        }
        // console.log(tabList.value);

        localStorage.setItem('tabList', JSON.stringify(tabList.value));

    }
    return {
        activeTab,
        tabList,
        changeTab,
        removeTab,
        onCloseTab
    }
}
