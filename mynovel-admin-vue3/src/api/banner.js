import axios from '~/axios';
import { queryParams } from '~/composables/until.js'
// banner列表
export function getBannerList(page, query = {}) {

    let r = queryParams(query);

    return axios.get(`/admin/banner/${page}${r}`)
}

// 增加banner
export function createBanner(data) {
    return axios.post(`/admin/banner`, data)
}

//修改banner
export function updateBanner(id, data) {
    return axios.post(`/admin/banner/${id}`, data)
}

//修改banner状态
export function changeBannerStatus(id, status) {
    return axios.post(`/admin/banner/${id}/update_status`, { status })
}
//删除banner
export function deleteBanner(id) {
    return axios.post(`/admin/banner/${id}/delete`)
}

