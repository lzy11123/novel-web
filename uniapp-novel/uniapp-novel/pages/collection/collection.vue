<template>
	<view>
		<view class="cal" :style="{height: `${statusBarHeight}px`}"></view>
		<search-box class="cal"></search-box>
		<tab-top :tabArr="tabList" :tabIndex="tabIndex" @changeTabIndex="changeTabIndex" class="cal">
		</tab-top>
		<swiper :current="tabIndex" @change="swiperTabIndex" :style="{height:`${calHeight}rpx`}">
			<swiper-item>
				<scroll-view scroll-y="true" :style="{height: `${calHeight}rpx`}" @scrolltolower="scrolltolower(tabI)">
					<collect-item v-for="(item,index) in books" :item="item" :key="item.id" @getId="getId"
						@showCancel="showCancel" @toDetail="toBookDetail"></collect-item>
					<view v-if="books.length == 0" class="flex align-center justify-center text-secondary font"
						style="height: 200rpx;">
						暂无收藏内容
					</view>
					<view v-else-if="books.length > 10 || loadText == '没有更多了'"
						class="flex align-center justify-center font text-light-muted " style="height: 80rpx;">
						{{loadText}}
					</view>
				</scroll-view>
			</swiper-item>
			<swiper-item>
				<scroll-view scroll-y="true" :style="{height: `${calHeight}rpx`}">
					<collect-item v-for="(item,index) in histroy" :item="item" :key="item.id" @getId="getId"
						@showCancel="showCancel" @toDetail="toBookDetail"></collect-item>
					<view v-if="histroy.length == 0" class="flex align-center justify-center text-secondary font"
						style="height: 200rpx;">
						暂无历史记录
					</view>
					<view v-else-if="histroy.length > 0" class="flex align-center justify-center font text-light-muted "
						style="height: 80rpx;">
						没有更多了
					</view>
				</scroll-view>
			</swiper-item>
		</swiper>

		<uni-popup ref="popup" type="bottom" @change="changePopupStatus">
			<view class="bg-white">
				<view class="flex align-center" style="height: 100rpx;" @click="cancelCollect">
					<iconfont iconId="icon-xingxing" iconColor="text-danger" class="px-3"></iconfont>
					<text class="font">{{tabIndex == 0 ? '取消收藏' : '删除记录'}}</text>
				</view>
				<view class="bg-hover-light" style="height: 15rpx;"></view>
				<view class="text-center" style="height: 110rpx;line-height: 110rpx;" @click="showCancel(false)">
					取消
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import tabTop from '@/components/tab-top.vue'
	import searchBox from '@/components/search-box.vue'
	import collectItem from '@/components/collect-item.vue'
	import uniPopup from '@/components/uni-popup/uni-popup.vue'

	import $u from '@/common/unit.js';

	import {
		uncollect,
		getCollectList
	} from '@/api/collection.js'

	export default {
		components: {
			tabTop,
			searchBox,
			collectItem,
			uniPopup
		},
		data() {
			return {
				calHeight: 0,
				tabIndex: 0,
				showCancelStatus: false,
				tabList: ['我的收藏', '历史记录'],
				statusBarHeight: this.$statusBarHeight,
				cancelId: 0,
				books: [],
				histroy: [],
				page: 1,
				loadText: '没有更多了',
			}
		},
		mounted() {
			$u.calSurplusHeight({
				pageID: this,
				pos: 'cal',
				isTabBarPage: true,
				success: val => this.calHeight = val
			})
		},
		onShow() {
			this.getData();
			this.getHistroyData();
		},
		methods: {
			getData() {
				getCollectList(this.page).then(res => {
					// console.log(res);
					if (this.page == 1) {
						this.books = res.data.list;
					} else {
						this.books = [...this.books, ...res.data.list];

						this.loadText = res.data.list.length >= 10 ? '上拉加载更多' : '没有更多了'
					}
				}).catch(err => {
					if (this.page > 1) {
						this.page--;
					}
					this.loadText = '上拉加载更多';
				})
			},
			getHistroyData() {
				let list = uni.getStorageSync('histroy');
				this.histroy = list ? JSON.parse(list) : [];
			},
			scrolltolower() {
				this.page++;
				this.getData();
			},
			swiperTabIndex(e) {
				this.tabIndex = e.detail.current;
			},
			// 获取tabIndex
			changeTabIndex(tabIndex) {
				this.tabIndex = tabIndex
			},
			// 拿到目标id
			getId(id) {
				// console.log(id);
				this.cancelId = id;
			},
			// 打开取消收藏选项
			showCancel(bol) {
				// console.log(bol);
				bol ? this.$refs.popup.open() : this.$refs.popup.close();
			},
			// 根据id获取index
			getIdIndex(id) {
				return this.books.findIndex(item => item.id == id);
			},
			// 删除收藏
			cancelCollect(type) {


				if (this.tabIndex == 0) {
					uncollect(this.cancelId).then(res => {
						// console.log(res);
						uni.showToast({
							title: '取消收藏成功',
							icon: 'none'
						});
						let index = this.getIdIndex(this.cancelId);
						this.books.splice(index, 1);
						this.showCancel(false);
					})
				} else {
					let index = this.histroy.findIndex(item => item.id == this.cancelId);
					this.histroy.splice(index, 1);
					// 删除后存储历史记录
					uni.setStorage({
						key: 'histroy',
						data: JSON.stringify(this.histroy),
					})
					uni.showToast({
						title: "删除成功",
						icon: "none",
					})
					this.showCancel(false);
				}



			},
			toBookDetail(id) {
				// console.log(id);
				if (id !== 0) {
					uni.navigateTo({
						url: "/pages/book-detail/book-detail?id=" + id,
					})
				}
			},
			// 监听改变显示状态
			changePopupStatus(e) {
				this.showCancelStatus = e.show;
			},

		},
		watch: {
			showCancelStatus(newVal) {
				if (newVal) {
					uni.hideTabBar({
						animation: true,
					})
				} else {
					uni.showTabBar({
						animation: true,
					})
				}
			}
		}
	}
</script>

<style>

</style>
