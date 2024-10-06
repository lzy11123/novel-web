<template>
	<view>
		<!-- #ifndef MP -->
		<page-title>修改密码</page-title>
		<!-- #endif -->
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">旧密码</text>
				<input class="flex-1 ml-auto  px-2" type="password" v-model="form.oldpassword" placeholder="请输入旧密码" />
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">新密码</text>
				<input class="flex-1 ml-auto  px-2" type="password" v-model="form.password" placeholder="请输入新密码" />

			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">新密码</text>
				<input class="flex-1  px-2" type="password" v-model="form.repassword" placeholder="请确认新密码" />

			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view class="px-4 py-3">
			<view @click="submit" style="padding: 15rpx 0;"
				class=" rounded-circle bg-main flex align-center justify-center font-md text-white"
				hover-class="bg-main-hover">
				确认修改
			</view>
		</view>
	</view>
</template>

<script>
	import pageTitle from '@/components/page-title.vue';
	import {
		changePassword
	} from '@/api/user.js'
	export default {
		components: {
			pageTitle,
		},
		data() {
			return {
				form: {
					oldpassword: '',
					password: '',
					repassword: '',
				}
			}
		},
		methods: {
			submit() {
				changePassword(this.form).then(res => {
					// console.log(res);
					uni.showToast({
						title: '修改成功,请重新登录',
						icon: 'none'
					})
					this.$store.commit('user/logout')
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/my/my'
						})
					}, 1000)
				})
			}
		}

	}
</script>

<style>

</style>
