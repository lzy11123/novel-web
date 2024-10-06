import request from './request.js';
import {
	queryParams
} from '../common/unit.js';
// 最新上架
export function getNewListt(page) {
	return request.get(`/api/newlist/${page}`);
}
// 热门推荐
export function getRecommend(page) {
	return request.get(`/api/recommend/${page}`);
}
// 最新排行
export function getReadList(page) {
	return request.get(`/api/readlist/${page}`);
}
//  根据分类id查小说
export function getCategoryNovel(id, page) {
	return request.get(`/api/cate/${id}/novel/${page}`)
}
// 小说详情信息
export function getNovel(id) {
	return request.get(`/api/read/novel/${id}`, {
		token: true,
		noJump: true,
	})
}
// 搜索小说
export function search(page, params) {
	let r = queryParams(params)
	return request.get(`/api/search/${page}${r}`)
}
