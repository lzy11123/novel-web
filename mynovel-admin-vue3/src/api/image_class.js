import axios from '~/axios'

// 图库列表
export function getImageClassList(page, limit = 10) {
    return axios.get(`/admin/imageclass/${page}?limit=${limit}`)
}


// 增加图库分类 data = {name:'xx',order:0}
export function createImageClass(data) {
    return axios.post('/admin/imageclass', data)
}

// 修改图库分类data = {name:'xx',order:0}
export function editImageClass(id, data) {
    return axios.post('/admin/imageclass/' + id, data)
}

// 删除图库分类
export function deleteImageClass(id) {
    return axios.post(`/admin/imageclass/${id}/delete`)
}