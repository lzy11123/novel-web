<template>
	<!-- 列表组件 -->
	<view>
		<block v-for="(item2,index2) in bookList" :key="index2">
			<view class="flex border-bottom p-2" hover-class="bg-light" @click="toBookDetail(item2.id)">
				<image class="flex-shrink" style="width: 200rpx;height: 240rpx;" :src="item2.cover" mode="aspectFill">
				</image>
				<view class="flex-1 ml-2" style="min-width: 0;height: 100%;">
					<view class="font-md font-weight-bold text-ellipsis">
						{{item2.name}}
					</view>
					<view class="font-sm text-light-black mt-2">
						{{item2.desc}}
					</view>
				</view>
			</view>

		</block>
	</view>
</template>

<script>
	export default {
		props: {
			bookList: {
				type: Array,
				default: [],
			}
		},
		methods: {
			toBookDetail(id) {
				if (id !== 0) {
					uni.navigateTo({
						url: "/pages/book-detail/book-detail?id=" + id,
					})
					
				}
			}
		}
	}
</script>

<style>
</style>
