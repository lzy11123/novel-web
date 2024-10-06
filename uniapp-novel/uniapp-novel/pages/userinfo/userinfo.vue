<template>
	<view>
		<!-- #ifndef MP -->
		<page-title>用户信息</page-title>
		<!-- #endif -->
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 120rpx;">
				<text class="text-dark">头像</text>
				<image class="ml-auto avatar" @click="uploadAvatar"
					:src="user.avatar?user.avatar :'../../static/images/head.png'" mode="aspectFill">
				</image>
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">用户</text>
				<input class="flex-1 ml-auto text-right px-2 text-hover-light" type="text" disabled
					:value="user.username" />
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">昵称</text>
				<input class="flex-1 ml-auto text-right px-2" type="text" v-model="user.nickname" />
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">手机</text>
				<input class="flex-1 ml-auto text-right px-2" type="text" v-model="user.phone" />
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view>
			<view class="flex align-center justify-between px-2 text-light-black" style="height: 100rpx;">
				<text class="text-dark">邮箱</text>
				<input class="flex-1 ml-auto text-right px-2" type="text" v-model="user.email" />
				<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
			</view>
			<view style="height: 2rpx;background-color: #f8f8f8;"></view>
		</view>
		<view class="px-4 py-3">
			<view @click="submit" style="padding: 15rpx 0;"
				class=" rounded-circle bg-main flex align-center justify-center font-md text-white">
				保存
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex';
	import pageTitle from '@/components/page-title.vue';
	import {
		changeUserinfo,
		upload
	} from '@/api/user.js'
	export default {
		components: {
			pageTitle,
		},
		data() {
			return {
				form: {
					nickname: '',
					phone: '',
					avator: '',
					email: '',
				}
			}
		},
		computed: {
			...mapState({
				user: state => state.user.user
			})
		},
		created() {

		},
		methods: {
			submit() {
				this.form = {
					nickname: this.user.nickname,
					phone: this.user.phone,
					avatar: this.user.avatar,
					email: this.user.email,
				};

				changeUserinfo(this.form).then(res => {
					uni.showToast({
						title: '修改成功',
						icon: 'none'
					})
				})
			},
			uploadAvatar() {
				uni.chooseImage({
					count: 1,
					sourceType: ['camera', 'album'],
					sizeType: ['original', 'compressed'],
					success: (res) => {

						uni.showLoading({
							title: '上传中'
						})

						upload({
							filePath: res.tempFilePaths[0]
						}).then(res => {
							this.user.avatar = res.data.url;
							uni.showToast({
								title: '上传成功，请等待加载',
								icon: 'none'
							})
							uni.hideLoading();
						}).catch(err => {
							uni.showToast({
								title: '上传失败',
								icon: 'none'
							})
							uni.hideLoading();
						})


					}
				})
			}
		}

	}
</script>

<style lang="less">
	.avatar {
		width: 100rpx;
		height: 100rpx;
		border-radius: 50%;
	}
</style>
