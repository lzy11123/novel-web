import axios from '~/axios';
import { queryParams } from '~/composables/until.js'
// 小说列表
export function getNovelList(page, query = {}) {

    let r = queryParams(query);

    return axios.get(`/admin/novel/${page}${r}`)
}

// 增加小说
export function createNovel(data) {
    return axios.post(`/admin/novel`, data)
}

//修改小说
export function updateNovel(id, data) {
    return axios.post(`/admin/novel/${id}`, data)
}

//修改小说状态
export function changeNovelStatus(id, status) {
    return axios.post(`/admin/novel/${id}/update_status`, { status })
}
//删除小说
export function deleteNovel(id) {
    return axios.post(`/admin/novel/${id}/delete`)
}

// 标签列表
export function getNovelTagList() {
    return axios.get(`/admin/novel/tag`)
}


