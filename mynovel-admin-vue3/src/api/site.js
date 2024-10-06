import axios from '~/axios';

// 获取站点设置信息
export function getSite() {
    return axios.get('/admin/site');
}

// 设置站点信息
export function createOrUpdateRule(data) {

    if (data.id) {
        return axios.post('/admin/site/' + data.id, data);
    } else {
        return axios.post('/admin/site', data);
    }
}