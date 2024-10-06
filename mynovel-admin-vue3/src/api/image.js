import axios from '~/axios';

// 获取指定分类下的图片列表
export function getImageList(id, page = 1, limit = 10) {
    return axios.get(`/admin/imageclass/${id}/image/${page}?limit=${limit}`)
}

// 修改图片名称
export function updateImageName(id, name) {
    return axios.post(`/admin/image/${id}`, { name })
}
// 删除图片 ids : []
export function deleteImage(ids) {
    return axios.post('/admin/image/delete_all', { ids })
}

// 图片上传地址
export const uploadImageAction = import.meta.env.VITE_APP_BASE_API + '/admin/image/upload'