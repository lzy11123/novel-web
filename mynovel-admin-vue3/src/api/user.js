import axios from '~/axios';
import { queryParams } from '~/composables/until.js'
// 用户列表
export function getUserList(page, query = {}) {

    let r = queryParams(query);

    return axios.get(`/admin/user/${page}${r}`)
}

// 增加用户
//  data = {"username": "黄金会员", "password": "100", "status": "1", "nickname": "10", "phone": "1000", "email": "500", "avatar": "32" user_level_id: 0, }
export function createUser(data) {
    return axios.post(`/admin/user`, data)
}

//修改用户
export function updateUser(id, data) {
    return axios.post(`/admin/user/${id}`, data)
}

//修改用户状态
export function changeUserStatus(id, status) {
    return axios.post(`/admin/user/${id}/update_status`, { status })
}
//删除会员
export function deleteUser(id) {
    return axios.post(`/admin/user/${id}/delete`)
}