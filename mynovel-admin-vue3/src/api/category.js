import axios from '~/axios'


// 商品分类列表
export function getCategoryList() {
    return axios.get('/admin/category');
}

//增加商品分类  data = {name : ''}
export function createCategory(data) {
    return axios.post(`/admin/category`, data)
}

//修改商品分类  data = {name : ''} 
export function updateCategory(id, data) {
    return axios.post(`/admin/category/${id}`, data)
}

//修改商品分类状态 
export function changeCategoryStatus(id, status) {
    return axios.post(`/admin/category/${id}/update_status`, { status })
}

//删除商品分类 
export function deleteCategory(id) {
    return axios.post(`/admin/category/${id}/delete`)
}

export function sortCategory(sortData) {
    return axios.post(`/admin/category/sort`, {
        sortdata: sortData
    })
}


// // 分类关联产品列表
// export function getCategoryGoods(category_id) {
//     return axios.get(`/admin/app_category_item/list?category_id=${category_id}`)
// }

// // 删除关联产品 分类产品关联ID
// export function deleteCategoryGoods(id) {
//     return axios.post(`/admin/app_category_item/${id}/delete`)
// }

// //关联产品  data = {"category_id": 5, "goods_ids": [ 49 ] }
// export function connectCategoryGoods(data) {
//     return axios.post(`/admin/app_category_item`, data)
// }


