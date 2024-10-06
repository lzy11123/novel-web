import request from './request.js'
// 注册 
export function register(data) {
	return request.post('/api/register', {
		...data
	})
}

// 用户登录
export function login(username, password) {
	return request.post('/api/login', {
		username,
		password
	})
}

// 用户退出
export function logout() {
	return request.post('/api/logout', {}, {
		token: true
	})
}

// 用户信息
export function getInfo() {
	return request.post('/api/user/getinfo', {}, {
		token: true
	});
}

// 修改密码
export function changePassword(data) {
	return request.post(`/api/updatepassword`, {
		...data
	}, {
		token: true,
	})
}

export function changeUserinfo(data) {
	return request.post(`/api/user/updateuserinfo`, {
		...data
	}, {
		token: true,
	})
}

export function upload(data) {
	return request.upload('/api/upload', {
		...data
	})
}