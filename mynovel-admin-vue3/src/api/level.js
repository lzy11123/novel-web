import axios from '~/axios';

// 会员等级列表
export function getUserLevelList(page) {
    return axios.get(`/admin/user_level/${page}`)
}

// 增加会员等级
//  data = {"name": "黄金会员", "level": "100", "status": "1", "discount": "10", "max_price": "1000", "max_times": "500", "id": "32" }
export function createUserLevel(data) {
    return axios.post(`/admin/user_level`, data)
}

//修改会员等级
export function updateUserLevel(id, data) {
    return axios.post(`/admin/user_level/${id}`, data)
}

// 修改会员等级状态
export function changeUserLevelStatus(id, status) {
    return axios.post(`/admin/user_level/${id}/update_status`, { status })
}
// 删除会员等级
export function deleteUserLevel(id) {
    return axios.post(`/admin/user_level/${id}/delete`)
}