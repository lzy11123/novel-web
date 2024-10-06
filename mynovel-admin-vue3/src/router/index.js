import { createRouter, createWebHashHistory } from 'vue-router';

import Admin from '~/layout/admin.vue'
import Login from '~/pages/login.vue'
import Index from '~/pages/index.vue'
import RoleList from '~/pages/role/list.vue'
import NotFound from '~/pages/404.vue'
import AccessList from '~/pages/access/list.vue'
import ManagerList from '~/pages/manager/list.vue'
import UserList from '~/pages/user/list.vue'
import LevelList from '~/pages/level/list.vue'
import ImageList from '~/pages/image/list.vue'
import CategoryList from '~/pages/category/list.vue'
import NovelList from '~/pages/novel/list.vue'
import NovelChapter from '~/pages/novel/chapter.vue'
import SiteIndex from '~/pages/site/index.vue'
import BannerList from '~/pages/banner/list.vue'
// 默认路由-所有用户共享路由
const routes = [
    {
        path: '/',
        component: Admin,
        name: 'admin',
        children: [

        ]
    },
    {
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            title: '登录'
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound,
        meta: {
            title: '404 Not Found'
        }
    },

];
const asyncRoutes = [

    {
        path: '/',
        name: '/',
        component: Index,
        meta: {
            title: '后台首页'
        }
    },
    {
        path: '/role/list',
        name: '/role/list',
        component: RoleList,
        meta: {
            title: '角色管理'
        }
    },
    {
        path: '/access/list',
        name: '/access/list',
        component: AccessList,
        meta: {
            title: '权限管理'
        }
    },
    {
        path: '/manager/list',
        name: '/manager/list',
        component: ManagerList,
        meta: {
            title: '管理员列表'
        }
    },
    {
        path: '/image/list',
        name: '/image/list',
        component: ImageList,
        meta: {
            title: '图片管理'
        }
    },
    {
        path: '/user/list',
        name: '/user/list',
        component: UserList,
        meta: {
            title: '用户管理'
        }
    }, {
        path: '/level/list',
        name: '/level/list',
        component: LevelList,
        meta: {
            title: '会员等级'
        }
    },
    {
        path: '/category/list',
        name: '/category/list',
        component: CategoryList,
        meta: {
            title: '分类管理'
        }
    },
    {
        path: '/novel/list',
        name: '/novel/list',
        component: NovelList,
        meta: {
            title: '小说列表'
        }
    },
    {
        path: '/novel/chapter/:id',
        name: '/novel/chapter/:id',
        component: NovelChapter,
        meta: {
            title: '小说章节列表'
        }
    },
    {
        path: '/banner/list',
        name: '/banner/list',
        component: BannerList,
        meta: {
            title: '轮播图列表'
        }
    },
    {
        path: '/site/index',
        name: '/site/index',
        component: SiteIndex,
        meta: {
            title: '站点设置'
        }
    },
];



export const router = createRouter({
    history: createWebHashHistory(),
    routes,
})



// 动态添加路由
export function addRoutes(menus) {
    // 是否有新的路由
    let hasNewRoutes = false;
    const findAndAddRoutes = (arr) => {
        arr.forEach(e => {
            let item = asyncRoutes.find(o => o.path == e.frontpath)

            if (item && !router.hasRoute(item.path)) {
                router.addRoute('admin', item);
                hasNewRoutes = true;
            }
            if (e.child && e.child.length > 0) {
                findAndAddRoutes(e.child);
            }
        })
    }
    findAndAddRoutes(menus)
    // console.log(router.getRoutes());
    return hasNewRoutes;

}