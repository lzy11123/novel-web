<template>
	<view>
		<!-- #ifndef MP -->
		<page-title theme="geryTheme" class="cal">图书详情</page-title>
		<!-- #endif -->
		<view class="geryTheme flex align-center py-2 px-2 cal" style="height: 300rpx;">
			<image style="width:200rpx;height: 260rpx;" class="rounded" :src="novel.cover" mode="aspectFill" lazy-load>
			</image>
			<view class="flex-1 px-2" style="min-width: 0;">
				<view class="text-ellipsis" style="font-size: 45rpx;">
					{{novel.name}}
				</view>
				<view class="font mt-1">
					作者：{{novel.author}}
				</view>
				<view class="flex align-center mt-2">
					<!-- <button class="book-btn flex-1 ">分享</button> -->
					<button class="book-btn flex-1 mx-2" @click="toCollect(novel.id)"> {{novel.collect ? '已收藏': '收藏'}}
					</button>
				</view>
			</view>
		</view>

		<view class="shadow">
			<tab-top :tabArr="tabList" :tabIndex="tabIndex" @changeTabIndex="changeTabIndex" class="cal">
			</tab-top>


			<swiper :style="{height:`${calHeight}rpx`}" :current="tabIndex" @change="swiperTabIndex">
				<swiper-item>
					<scroll-view scroll-y :style="{height:`${calHeight}rpx`}">
						<view>
							<view class=" py-2 flex justify-center text-light-black">
								——简介——
							</view>
							<view class="px-2" style="line-height: 60rpx;">
								{{ novel.desc }}
							</view>
						</view>
					</scroll-view>
				</swiper-item>
				<swiper-item>
					<scroll-view scroll-y :style="{height: `${calHeight}rpx`}">
						<block v-for="(item ,index) in chapterCatalog" :key="item.id">
							<view class="w-100 p-2 text-ellipsis border-bottom" hover-class="bg-light"
								@click="toReadingPage(item.id)">
								{{item.name}}
							</view>
						</block>
					</scroll-view>
				</swiper-item>
			</swiper>
			<!-- 具体内容 -->

			<!-- 目录 -->

		</view>
	</view>
</template>

<script>
	import test from '@/common/test.js';
	import pageTitle from '@/components/page-title.vue';
	import tabTop from '@/components/tab-top.vue';
	import $u from '@/common/unit.js';
	import {
		getNovel
	} from '@/api/novel.js'
	import {
		collect
	} from '@/api/collection.js'
	export default {
		components: {
			pageTitle,
			tabTop
		},
		data() {
			return {
				novelId: 0,
				novel: {},
				calHeight: 0,
				tabIndex: 0,
				tabList: ['详情', '目录'],
				chapterCatalog: [],
			}
		},
		onLoad(e) {
			if (e.id) {
				this.novelId = e.id;
				this.getData()
			} else {
				uni.showToast({
					title: '该记录不存在',
					icon: 'none'
				})
				return uni.switchTab({
					url: '/pages/index/index'
				})
			}
		},
		mounted() {
			$u.calSurplusHeight({
				pageID: this,
				pos: 'cal',
				success: val => this.calHeight = val
			})

		},
		methods: {
			// 获取数据
			getData() {
				getNovel(this.novelId).then(res => {

					this.novel = res.data.novel;
					this.chapterCatalog = res.data.list;


					// 存储到历史记录中

					let list = uni.getStorageSync('histroy');
					list = list ? JSON.parse(list) : [];

					let index = list.findIndex(item => {
						return item.id === this.novelId
					})

					if (index === -1 && res.data.novel.id) {
						console.log(this.novel.id)
						list.unshift({
							id: this.novelId,
							novel: this.novel
						});

					} else {
						if (index != -1) {
							this.toFirst(list, index);
						}
					}
					uni.setStorage({
						key: 'histroy',
						data: JSON.stringify(list),
					})
					// 存储到历史记录中
				})
			},
			// 数组置顶
			toFirst(arr, index) {
				if (index != 0) {
					arr.unshift(arr.splice(index, 1)[0]);
				}
				return arr;
			},
			swiperTabIndex(e) {
				this.tabIndex = e.detail.current;
			},
			// 获取tabIndex
			changeTabIndex(tabIndex) {
				this.tabIndex = tabIndex
			},
			// 跳转阅读页
			toReadingPage(id) {

				uni.navigateTo({
					url: `/pages/reading/reading?chapterId=${id}`
				})
			},
			toCollect(id) {
				if (this.novel.collect) {
					return uni.showToast({
						title: "您已经收藏过了",
						icon: 'none'
					})
				}
				collect(id).then(res => {
					this.novel.collect = true;
					uni.showToast({
						title: "收藏成功",
						icon: 'none'
					})
				})
			}
		}
	}
</script>

<style lang="less">
	.geryTheme {
		background-color: #a8b0c3;
	}

	.book-btn {
		height: 80rpx;
		line-height: 80rpx;
		font-size: 30rpx;
	}
</style>