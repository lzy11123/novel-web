<template>
	<!-- 搜索组件 -->
	<view class="flex align-center  mx-2 border rounded bg-light" style="height:60rpx;opactity:0.8" @click="goSearch">
		<iconfont class="p-1 flex align-center" iconId="icon-tubiao11" :iconSize="25" iconColor="text-light-muted">
		</iconfont>
		<view class="flex font-sm text-light-black">
			搜索书名或作者名
		</view>
	</view>
</template>

<script>
	export default {
		methods: {

			goSearch() {
				uni.navigateTo({
					url: '/pages/search/search'
				})
			}

		}
	}
</script>

<style>

</style>
