import axios from 'axios'
import { Toast, getToken } from '~/composables/until.js'
import store from './store';
const service = axios.create({
    // 基础路径
    baseURL: import.meta.env.VITE_APP_BASE_API,
})

// 请求拦截器
service.interceptors.request.use(config => {

    // header头自动添加token
    let token = getToken();
    if (token) {
        config.headers['token'] = token;
    }

    return config
})

// 响应拦截器
service.interceptors.response.use(res => {
    // console.log(res);
    // 对响应数据做处理
    return res.request.responseType == 'blob' ? res.data : res.data.data;
}, error => {
    // console.log('响应拦截器：', error);
    let msg = error.response.data.msg || error.response.data.message || '请求失败';
    if (msg == '非法token，请先登录') {
        store.dispatch('logout').finally(() => {
            location.reload();
        });
    }

    Toast(msg, 'error')
    return Promise.reject(error)
})

export default service