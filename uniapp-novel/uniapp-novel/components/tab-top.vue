<template>
	<view class="flex align-center border-bottom" style="height: 80rpx;justify-content: space-around;">
		<block v-for="(item,index) in tabArr" :key="index">
			<view class="flex flex-column align-center justify-between" style="height: 100%;"
				@click="changeTabIndex(index)">
				<view class="text-light-black animate__animated  animate__faster" style="padding-top: 15rpx;"
					:class="tabIndex === index ? 'animate__pulse': ''">
					{{item}}
				</view>
				<view v-if="tabIndex === index" class="rounded animate__animated animate__zoomIn animate__faster"
					style="width: 80rpx;height: 8rpx;background-color: #fb5f39;">
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		name: "tab-top",
		data() {
			return {
			};
		},
		props: {
			tabIndex: {
				type: Number,
				default:0,
			},
			tabArr: {
				type: Array,
				default: [],
			}
		},
		methods: {
			changeTabIndex(index) {
				this.$emit('changeTabIndex', index);
			}
		}
	}
</script>

<style>

</style>
