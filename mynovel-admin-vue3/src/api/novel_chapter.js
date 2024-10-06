import axios from '~/axios';
import { queryParams } from '~/composables/until.js'
// 小说列表
export function getNovelChapterList(novel_id, page = 1, limit = 10) {
    return axios.get(`/admin/novel/${novel_id}/noveldetail/${page}?limit=${limit}`)
}

// 增加小说
export function createNovelChapter(data) {
    return axios.post(`/admin/noveldetail`, data)
}

//修改小说
export function updateNovelChapter(id, data) {
    return axios.post(`/admin/noveldetail/${id}`, data)
}

//修改小说状态
export function changeNovelChapterStatus(id, status) {
    return axios.post(`/admin/noveldetail/${id}/update_status`, { status })
}
//删除小说
export function deleteNovelChapter(id) {
    return axios.post(`/admin/noveldetail/${id}/delete`)
}
