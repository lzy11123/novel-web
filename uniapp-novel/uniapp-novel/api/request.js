import $store from '../store/index.js';
export default {
	// 全局配置
	common: {

		// #ifndef H5
		// baseUrl: '/api',
		baseUrl: 'http://www.lin123.com',
		// #endif
		// #ifdef H5
		baseUrl: '/api', //本地开发
		// baseUrl: 'http://www.lin123.com', //线上开发
		// #endif

		header: {
			'Content-Type': 'application/json;charset=UTF-8',
			// 'Content-Type': 'application/x-www-form-urlencoded'
		},
		data: {},
		method: 'GET',
		dataType: 'json',
		token: false
	},
	// 请求 返回promise
	request(options = {}) {
		// 组织参数
		options.url = this.common.baseUrl + options.url
		options.header = options.header || this.common.header
		options.data = options.data || this.common.data
		options.method = options.method || this.common.method
		options.dataType = options.dataType || this.common.dataType
		options.token = options.token === true ? true : this.common.token

		// 请求
		return new Promise((res, rej) => {
			// 请求之前验证...
			// token验证s
			if (options.token) {

				let token = $store.state.user.token;

				// 往header头中添加token
				options.header.token = token;
				// 二次验证
				if (!token && options.noJump !== true) {
					uni.showToast({
						title: '请先登录',
						icon: 'none'
					});
					// token不存在时跳转
					uni.navigateTo({
						url: '/pages/login/login',
					});
					return rej("请先登录")
				}

			}

			// 请求中...
			uni.request({
				...options,
				success: (result) => {
					console.log(result)
					// 返回原始数据
					if (options.native) {
						return res(result)
					}
					// 服务端失败
					if (result.statusCode !== 200) {
						
						if (options.toast != false) {
							uni.showToast({
								title: result.data.msg || '请求异常',
								icon: 'none'
							})
						}

						if (result.data.msg == '请携带token') {
							uni.showToast({
								title: result.data.msg || '请求异常',
								icon: 'none'
							})
						}

						if (result.data.msg == '非法token，请先登录') {
							$store.commit('user/logout')
						}
						return false;
					}
					// 其他验证...
					// 成功
					let data = result.data
					res(data)
				},
				fail: (error) => {
					console.log(error)
					uni.showToast({
						title: error.errMsg || '请求失败',
						icon: 'none'
					});
					return rej(error)
				}
			});
		})
	},
	// get请求
	get(url, options = {}) {
		options.url = url
		options.data = {}
		options.method = 'GET'
		return this.request(options)
	},
	// post请求
	post(url, data = {}, options = {}) {
		options.url = url
		options.data = data
		options.method = 'POST'
		return this.request(options)
	},
	// delete请求
	del(url, data = {}, options = {}) {
		options.url = url
		options.data = data
		options.method = 'DELETE'
		return this.request(options)
	},
	// 上传文件
	upload(url, data, onProgress = false) {
		return new Promise((result, reject) => {
			// 上传之前验证
			let token = uni.getStorageSync('token')

			if (!token) {
				uni.showToast({
					title: '请先登录',
					icon: 'none'
				});
				// token不存在时跳转
				return uni.navigateTo({
					url: '/pages/login/login',
				});
			}
			token = JSON.parse(token)

			const uploadTask = uni.uploadFile({
				url: this.common.baseUrl + url,
				filePath: data.filePath,
				name: data.name || "file",
				header: {
					token
				},
				success: (res) => {
					// console.log(res);
					if (res.statusCode != 200) {

						result(false)
						return uni.showToast({
							title: '上传失败',
							icon: 'none'
						});
					}
					let message = JSON.parse(res.data)

					result(message);
				},
				fail: (err) => {
					// console.log(err);
					reject(err)
				}
			})

			uploadTask.onProgressUpdate((res) => {
				if (typeof onProgress === 'function') {
					onProgress(res.progress)
				}
			});

		})
	}
}