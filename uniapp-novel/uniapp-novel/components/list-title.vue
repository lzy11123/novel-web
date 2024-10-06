<template>
	<!-- 列表头部组件 -->
	<view class="flex align-center p-2">
		<view class="font-md">
			<slot name="title"></slot>
		</view>
		<view class="ml-auto flex align-center" v-if="tip" @click="toPage">
			<view class="font text-light-black">
				<slot name="tips">查看全部</slot>
			</view>
			<iconfont iconId="icon-iconfonti" iconColor="text-light-black"></iconfont>
		</view>
	</view>
</template>

<script>
	export default {
		name: "list-title",
		props: {
			tip: {
				type: Boolean,
				default: true,
			},
			item: {
				type: Object,
				default: null,
			}
		},
		data() {
			return {

			};
		},
		methods: {
			toPage() {
				uni.navigateTo({
					url: `/pages/cate-list/cate-list?id=${this.item.id}&cate=${this.item.name}`,
				})
			}
		}
	}
</script>

<style>

</style>
