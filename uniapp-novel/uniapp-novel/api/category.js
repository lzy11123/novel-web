import request from './request.js'

export function getCategoryList() {
	return request.get('/api/category')
}
