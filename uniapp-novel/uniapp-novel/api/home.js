import request from './request.js'

export function getHomeData() {
	return request.get('/api/home');
}
