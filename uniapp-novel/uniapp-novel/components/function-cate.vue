<template>
	<!-- 分类组件 -->
	<view class="flex flex-wrap align-center">
		<block v-for="(item,index) in cateList" :key="index">
			<view style="width: 25%;" class="flex align-center flex-column" @click="switchToPage(item)">
				<image :src="item.src" style="width: 64rpx;height: 64rpx;" mode="aspectFit"></image>
				<text class="mt-1 font-sm text-light-black">{{item.title}}</text>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		props: {
			cateList: {
				type: Array,
				default: [],
			}
		},
		methods: {
			switchToPage(item) {

				if (item.type == 'switchTab') {
					return uni.switchTab({
						url: item.path
					})
				}

				uni.navigateTo({
					url: item.path
				})

			}
		}
	}
</script>

<style>

</style>
