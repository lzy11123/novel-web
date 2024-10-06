// v-permission="['getStatistics3']"
import store from '~/store'
// 判断是否有权限
function hasPermission(value, el = false) {

    if (!Array.isArray(value)) {
        throw new Error(`需要配置权限,例如v-permission=['getData']`)
    }
    let hasAuth = value.findIndex(v => {
        // console.log(v);
        // console.log(store.state.ruleNames.includes(v));
        return store.state.ruleNames.includes(v);
    }) != -1;


    if (el && !hasAuth) {
        el.parentNode && el.parentNode.removeChild(el);
    }

    return hasAuth;

}

// 自定义指令-鉴权指令
export default {
    install(app) {
        // console.log(app);
        app.directive('permission', {
            mounted(el, binding) {
                // console.log(el, binding);
                hasPermission(binding.value, el);
            },
        })
    }
}