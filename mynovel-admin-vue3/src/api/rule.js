import axios from '~/axios';

// 菜单权限列表
export function getRuleList(page) {
    // console.log(page);
    return axios.get(`/admin/rule/${page}`)
}

// 新增菜单权限  data= {rule_id,menu,name,condition,method,status,order,icon,frontpath}
export function createRule(data) {
    return axios.post('/admin/rule', data)
}

// 修改菜单权限  data= {rule_id,menu,name,condition,method,status,order,icon,frontpath}
export function updateRule(id, data) {
    return axios.post('/admin/rule/' + id, data)
}

// 删除菜单权限
export function deleteRule(id) {
    return axios.post(`/admin/rule/${id}/delete`)
}

// 修改菜单权限状态
export function changeRuleStatus(id, status) {
    return axios.post(`/admin/rule/${id}/update_status`, { status })
}