<template>
	<view class="flex border-bottom p-2 position-relative" hover-class="bg-light" @click="toDetail(item.novel.id)">
		<image class="flex-shrink" style="width: 180rpx;height: 220rpx;" :src="item.novel.cover" mode="aspectFill">
		</image>
		<view class="flex-1 ml-2 " style="min-width: 0;height: 100%;">
			<view class="font-md font-weight-bold text-ellipsis">
				{{item.novel.name}}
			</view>
			<view class="font-sm text-light-black mt-2 text-ellipsis3">
				{{item.novel.desc}}
			</view>
			<iconfont iconId="icon-diandian" class="position-absolute right-0 " @click="init"></iconfont>
		</view>
	</view>
</template>

<script>
	export default {
		name: "collect-item",
		props: {
			item: {
				type: Object,
				default: {}
			}
		},
		methods: {
			init() {
				this.throwId(this.item.id);
				this.showCancel(true);
			},
			throwId(id) {
				this.$emit('getId', id);
			},
			showCancel(bol) {
				this.$emit('showCancel', bol)
			},
			toDetail(id) {
				this.$emit('toDetail', id);
			},

		}
	}
</script>

<style>

</style>
