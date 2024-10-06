<template>
	<!-- 轮播图组件 -->
	<view class="m-2">
		<swiper :indicator-dots="true" :autoplay="true" circular indicator-active-color="#fff" style="height:380rpx"
			@change="getCurrentIndex">
			<swiper-item v-for="(item,index) in list" :key="index">
				<image class="w-100 animated " :class="currentIndex == index ? 'slide-scale' : ''" :src="item.src"
					mode="aspectFill"></image>
			</swiper-item>
		</swiper>

	</view>
</template>

<script>
	export default {
		props: {
			list: {
				type: Array,
				default: [],
			}
		},
		data() {
			return {
				currentIndex: 0,
			}
		},
		methods: {
			getCurrentIndex(e) {
				// console.log(e.detail.current);
				this.currentIndex = e.detail.current;
			}
		}
	}
</script>

<style scoped lang="less">

</style>
