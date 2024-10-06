import request from './request.js'


// 小说章节详情
export function getChapterInfo(id) {
	return request.get(`/api/chapter/${id}`);
}

// 获取小说章节列表
export function getNovelChapter(id) {
	return request.get(`/api/novel/${id}/chapter`)
}
