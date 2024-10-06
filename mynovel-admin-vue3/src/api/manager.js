import axios from '~/axios';

// 登录接口
export function login(username, password) {
    return axios.post('/admin/login', {
        username,
        password
    })
}


// 退出登录
export function logout() {
    return axios.post('/admin/logout')
}


// 获取管理员信息
export function getinfo() {
    return axios.post('/admin/manager/getinfo')
}

//修改密码 data = {oldpassword,password,repassword}
export function updatepassword(data) {
    return axios.post('/admin/updatepassword', data)
}

import { queryParams } from '~/composables/until.js'

// 管理员列表
export function getManagerList(page, query = {
    limit: 10,
    keyword: '',
}) {
    let r = queryParams(query);

    return axios.get(`/admin/manager/${page}${r}`)
}

// 新增 
export function createManager(data) {
    // console.log(data);
    return axios.post('/admin/manager', data)
}

// 修改  data: {name,status,desc	}
export function updateManager(id, data) {
    return axios.post('/admin/manager/' + id, data)
}


// 删除管理员
export function deleteManager(id, data) {
    return axios.post(`/admin/manager/${id}/delete`, data);
}
// 管理员状态
export function changeManagerStatus(id, status) {
    return axios.post(`/admin/manager/${id}/update_status`, { status });
}