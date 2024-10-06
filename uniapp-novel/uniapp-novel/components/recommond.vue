<template>
	<!-- 推荐组件 -->
	<view>
		<list-title :tip="false">
			<template #title>猜你喜欢</template>
			<template #tips>更多推荐</template>
		</list-title>
		<view class="flex align-center flex-wrap">
			<block v-for="(item,index) in readBooks" :key="index">
				<view class="flex flex-column align-center px-1" style="width: 250rpx;" @click="toBookDetail(item.id)">
					<image style="width: 200rpx;height: 240rpx;" :src="item.cover" mode="aspectFill"></image>
					<view class="w-100 p-1 font-sm text-light-black text-ellipsis text-center">{{item.name}}</view>
				</view>
			</block>
		</view>
	</view>
</template>

<script>
	import listTitle from './list-title.vue';
	export default {
		components: {
			listTitle
		},
		props: {
			readBooks: {
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
