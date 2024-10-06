import axios from "~/axios";
import { queryParams } from '~/composables/until.js'
// 获取角色列表
export function getRoleList(page) {
    return axios.get('/admin/role/' + page)
}

// 新增角色  data: {name,status,desc	}
export function createRole(data) {
    return axios.post('/admin/role', data)
}

// 修改角色data: {name,status,desc	}
export function updateRole(id, data) {
    return axios.post('/admin/role/' + id, data)
}


// 修改角色状态 
export function changeRoleStatus(id, status) {
    return axios.post(`/admin/role/${id}/update_status`, { status })
}

// 删除角色
export function deleteRole(id) {
    return axios.post(`/admin/role/${id}/delete`)
}

// 配置权限
export function setRoleRules(id, ruleIds) {
    return axios.post(`/admin/role/set_rules`, {
        id,
        rule_ids: ruleIds,
    })
}