import request from './request.js'

// 收藏
export function collect(novel_id) {
	return request.post(`/api/user/collect`, {
		novel_id,
	}, {
		token: true,

	})
}

// 收藏
export function uncollect(id) {
	return request.post(`/api/user/uncollect`, {
		id,
	}, {
		token: true,
	})
}

// 收藏列表
export function getCollectList(page) {
	return request.get(`/api/collect/${page}`, {
		token: true,
		noJump: true,
	})
}
