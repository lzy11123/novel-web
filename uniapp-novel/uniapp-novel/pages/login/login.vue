<template>
	<view>
		<!-- #ifndef MP -->
		<view :style="'height:'+statusBarHeight+'px;'"></view>
		<view style="height: 44px;" class="flex align-center">
			<view style="width: 44px;height:44px" class="flex align-center justify-center animated fase"
				hover-class="text-main pulse" @click="back">
				<text class="iconfont icon-jiantou-copy font-lg"></text>
			</view>
		</view>
		<!-- #endif -->
		<view class="flex align-center justify-center font-lg text-muted font-weight-bold"
			style="margin: 100rpx 0 80rpx 0;">
			{{type === 'login' ?'欢迎回来':'欢迎注册'}}
		</view>
		<view class="px-4">
			<input type="text" v-model="form.username" class="uni-input bg-light rounded mb-4 p-2"
				placeholder="手机号/用户名/邮箱" />
			<input type="password" v-model="form.password" class="uni-input bg-light rounded mb-4  p-2"
				placeholder="请输入密码" />
			<input v-if="type !== 'login'" type="password" v-model="form.repassword"
				class="uni-input bg-light rounded mb-4  p-2" placeholder="请输入确认密码" />
		</view>

		<view class="px-4">

			<view @click="submit" style="padding: 15rpx 0;"
				class=" rounded-circle bg-main flex align-center justify-center font-md text-white"
				hover-class="bg-main-hover">
				{{type === 'login' ? '登 录' : '注 册'}}
			</view>
		</view>




		<view class="flex align-center justify-center py-5">

			<view class="mx-2 font-sm text-muted" @click="changeType">
				{{type === 'login' ? '注册账号' : '登录'}}
			</view>

		</view>

	</view>
</template>

<script>
	import {
		login,
		register
	} from '@/api/user.js'
	export default {
		data() {
			return {
				statusBarHeight: 0,
				type: "login",
				form: {
					username: "",
					password: "",
					repassword: "",
				}
			}
		},
		onLoad() {
			this.statusBarHeight = uni.getSystemInfoSync().statusBarHeight;
		},
		methods: {
			changeType() {
				this.type = this.type === 'login' ? 'register' : 'login';
				this.form = {
					username: "",
					password: "",
					repassword: "",
				}
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			submit() {
				let msg = this.type === 'register' ? '注册' : '登录';


				if (this.type == 'login') {
					login(this.form.username, this.form.password).then(res => {
						uni.showToast({
							title: msg + "成功",
							icon: "none",
						})
						this.$store.dispatch('user/login', res.data);
						uni.switchTab({
							url: "/pages/my/my"
						})
					})
				} else {

					register(this.form).then(res => {
						uni.showToast({
							title: '注册成功',
							icon: "none",
						})
						// 注册
						if (this.type == 'register') {
							this.changeType();
						}
					})
				}
			}
		}
	}
</script>

<style>

</style>
