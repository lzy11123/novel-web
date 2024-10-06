import {
	logout
} from '@/api/user.js'
export default {
	namespaced: true,
	state: {
		user: {},
		token: '',
	},
	getters: {

	},
	mutations: {
		// 初始化登录状态
		initUser(state) {
			let userInfo = uni.getStorageSync('user');
			if (userInfo) {
				userInfo = JSON.parse(userInfo);
				state.user = userInfo;
				state.token = userInfo.token;
			}
		},
		// 退出登录
		logout(state) {
			state.user = {};
			state.token = '';
			uni.removeStorageSync('user');
			uni.removeStorageSync('token');
		}
	},
	actions: {
		// 登录
		login({
			state
		}, user) {
			state.user = user;
			state.token = user.token;
			uni.setStorageSync('user', JSON.stringify(user));
			uni.setStorageSync('token', JSON.stringify(user.token));
		},

	}
}
