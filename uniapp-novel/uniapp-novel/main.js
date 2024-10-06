import App from './App'

// #ifndef VUE3
import Vue from 'vue'
Vue.config.productionTip = false
App.mpType = 'app'

// 引入vuex 仓库
import store from 'store/index.js'

// 注册全局iconfont组件
import iconfont from 'components/iconfont.vue'
Vue.component('iconfont', iconfont)
// 获取状态导航高度
// #ifdef APP-PLUS
Vue.prototype.$statusBarHeight = uni.getSystemInfoSync().statusBarHeight;
// #endif
// #ifndef APP-PLUS
Vue.prototype.$statusBarHeight = 10;
// #endif


// 挂在仓库
Vue.prototype.$store = store;


const app = new Vue({
	...App
})
app.$mount()
// #endif
