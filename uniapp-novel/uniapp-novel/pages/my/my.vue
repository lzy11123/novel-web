<template>
	<view>
		<view :style="{height:`${statusBarHeight}px`}">

		</view>
		<view class="flex align-center justify-between px-2" style="height: 200rpx;">
			<view class="flex align-center">
				<image :src="user.avatar ? user.avatar : '../../static/images/head.png' " mode="aspectFill"
					style="width: 130rpx;height: 130rpx;border-radius: 50%;"></image>
				<text v-if="user.token" class="pl-2">{{user.nickname || user.username}}</text>
				<text v-else class="pl-2" @click="toLogin">请先登录</text>

			</view>
			<view class="flex align-center justify-center rounded-circle py-1 px-2" style="background-color: #ecf0f3;">
				{{user.userLevel ? user.userLevel.name : '普通会员'}}
			</view>
		</view>

		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;"
				hover-class="bg-light" @click="navigateTo('userinfo')">
				<text>设置</text>
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 12rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;"
				hover-class="bg-light" @click="navigateTo('password')">
				<text>修改密码</text>
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 12rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;"
				hover-class="bg-light" @click="logout">
				<text>切换账号</text>
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 12rpx;background-color: #f8f8f8;"></view>
		</view>
	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex';
	import {
		logout
	} from '@/api/user.js'
	export default {
		data() {
			return {
				statusBarHeight: this.$statusBarHeight
			}
		},
		computed: {
			...mapState({
				user: state => state.user.user,
			})
		},
		onLoad() {
		},
		methods: {
			toLogin() {
				uni.navigateTo({
					url: '/pages/login/login'
				})
			},
			logout() {
				let _this = this;
				if (this.$store.state.user.token) {
					uni.showModal({
						title: '提示',
						content: '是否要退出登录？',
						success: function(res) {
							if (res.confirm) {
								console.log('用户点击确定');
								logout().then(res => {
									_this.$store.commit('user/logout')
								})
							}
						}
					});
				} else {
					uni.navigateTo({
						url: '/pages/login/login'
					})
				}
			},
			//跳转
			navigateTo(key) {
				uni.navigateTo({
					url: `/pages/${key}/${key}`
				})
			}
		}
	}
</script>

<style>

</style>
