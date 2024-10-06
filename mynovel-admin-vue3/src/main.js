import { createApp } from 'vue'

// 引入ElementPlus
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import zhCn from 'element-plus/es/locale/lang/zh-cn'
// 从 @element-plus/icons-vue 中导入所有图标并进行全局注册
import * as ElementPlusIconsVue from '@element-plus/icons-vue'

// windicss使用
import 'virtual:windi.css'

import App from './App.vue'


// 路由
import { router } from './router'
// import { toLine } from './utils'

import store from './store'

import './pemission.js'

const app = createApp(App)

// 自定义权限指令
import permission from '~/directives/permission.js'

app.use(permission)



// 使用ElementPlus
// 进行ElementPlus图标全局注册
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
}
app.use(ElementPlus, {
    locale: zhCn,
})



app.use(router);
app.use(store)






app.mount('#app')
