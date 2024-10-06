<template>
	<view>
		<view class="cal" :style="{height:`${statusBarHeight}px`}"></view>
		<search-box class="cal"></search-box>
		<view class="cal" style="height:20rpx"></view>
		<view class="flex" style="background-color: #f0f3f8;">
			<!-- 左侧 -->
			<scroll-view scroll-y="true" class="font text-light-black" style="width: 180rpx;"
				:style="{height:`${calHeight}rpx`}">
				<block v-for="(item, index) in leftListRes" :key="index">
					<view :class="{'selected' : leftIndex == index}" class="px-2"
						style="height: 80rpx;line-height: 80rpx;" @tap="leftToRight(index)">{{item}}
					</view>
				</block>
			</scroll-view>
			<!-- 右侧 -->
			<scroll-view scroll-y="true" :scroll-into-view="rightIndex" scroll-with-animation class="flex-1"
				:style="{height:`${calHeight}rpx`}" @scroll="rightToLeft">
				<block v-for="(item, index) in categoryList" :key="index">
					<view class="px-2" style="min-height: 250rpx;margin-bottom: 70rpx;" :id="`right${index}`">
						<!-- 顶部标题 -->
						<view class="flex align-center justify-center" style="height: 80rpx;">
							<text class="mr-1" @click="toCate(item)">{{item.name}}</text>
							<iconfont iconId="icon-youjiantou" iconSize="40" @click="toCate(item)"></iconfont>
						</view>
						<!-- 底部内容 -->
						<view class="bg-white font flex flex-wrap rounded">
							<block v-for="(mitem,mIndex) in item.child" :key="mIndex">
								<view class="flex align-center justify-center py-1" style="width: calc(100% / 3);"
									@click="toSearch(mitem.name)">
									{{mitem.name}}
								</view>

							</block>
						</view>
					</view>
				</block>
				<!-- 占位 -->
				<view :style="{height:`${calHeight- 320}rpx`}"></view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	import $u from '@/common/unit.js'
	import searchBox from '@/components/search-box.vue'
	import iconfont from '@/components/iconfont.vue'
	import {
		getCategoryList
	} from '@/api/category.js'
	export default {
		components: {
			searchBox,
			iconfont
		},
		data() {
			return {
				statusBarHeight: this.$statusBarHeight,
				calHeight: 0,
				leftIndex: 0,
				rightIndex: `right${0}`,
				categoryList: [],
			}
		},
		computed: {
			// 左侧数据
			leftListRes() {
				let res = this.categoryList.map(item => item.name)
				return res;
			},
		},
		mounted() {
			// 动态获取高度
			$u.calSurplusHeight({
				pageID: this,
				pos: 'cal',
				isTabBarPage: true,
				success: res => {
					// console.log(res)
					this.calHeight = res;
				}
			})
			this.getData();
		},
		methods: {
			// 获取数据
			getData() {
				getCategoryList().then(res => {
					this.categoryList = res.data;
				})
			},
			// 左联动右
			leftToRight(index) {
				// console.log(index)
				this.rightIndex = `right${index}`;
			},
			// 右联动左
			rightToLeft(e) {
				// console.log(e)
				let curScrollTop = e.detail.scrollTop
				let standardVal = $u.Topx(320);
				let curIndex = Math.round(curScrollTop / standardVal)
				this.leftIndex = curIndex
			},
			toSearch(key) {
				uni.navigateTo({
					url: "/pages/search/search?keyword=" + key,
				})
			},
			toCate(item) {
				uni.navigateTo({
					url: `/pages/cate-list/cate-list?id=${item.id}&cate=${item.name}`,
				})
			}
		}
	}
</script>

<style scoped>
	.selected {
		color: #fff;
		background-color: #17A2B8;
	}
</style>
