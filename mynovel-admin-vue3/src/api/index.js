import axios from '~/axios';
// 首页信息
export function getStatistics1() {
    return axios.get('/admin/statistics1')
}
// echart图表数据
export function getStatistics2() {
    return axios.get(`/admin/statistics2`)
}
// echart图表数据
export function getStatistics3(type) {
    return axios.get(`/admin/statistics3?type=${type}`)
}