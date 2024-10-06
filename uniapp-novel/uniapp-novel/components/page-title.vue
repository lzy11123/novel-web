<template>
	<view>
		<view :style="{ height: `${statusBarHeight}px` }" :class="theme"></view>
		<view class="fixed-top" :class="theme" :style="{ height: `${statusBarHeight}px` }"></view>
		<view class="flex align-center position-fixed w-100" :class="theme"
			:style="{ height: `60rpx`, top: `${statusBarHeight}px` }" style="z-index: 10">
			<iconfont class="px-2" iconId="icon-jiantou-copy" @click="quit"></iconfont>
			<view class="font-md">
				<slot>榜单</slot>
			</view>
		</view>
		<view style="height:60rpx"></view>
		<view :style="{ height: `${statusBarHeight}px` }" :class="theme"></view>
	</view>
</template>

<script>
	export default {
		props: {
			theme: {
				type: String,
				default: "bg-white",
			},
		},
		data() {
			return {
				statusBarHeight: this.$statusBarHeight,
			};
		},
		methods: {
			// 返回
			quit() {
				uni.navigateBack({
					delta: 1,
				});
			},
		},
	};
</script>

<style>
</style>
