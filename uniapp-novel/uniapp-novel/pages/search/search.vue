<template>
	<view>
		<view class="position-fixed w-100 bg-white" style="z-index: 1000;">
			<view :style="'height:' + statusBarHeight + 'px'"></view>
			<view class="flex align-center  mx-2 border rounded bg-light" style="height:60rpx;opactity:0.8">
				<iconfont class="p-1 flex align-center" iconId="icon-tubiao11" :iconSize="25"
					iconColor="text-light-muted">
				</iconfont>
				<input class="flex-1 font-sm text-light-black" v-model="form.keyword" type="text"
					placeholder="搜索书名或作者名" />
				<button class="flex align-center justify-center font py-0" type="default" style="height: 60rpx"
					@click="toSearch">
					搜索
				</button>

			</view>
			<view :style="'height:' + statusBarHeight + 'px'"></view>
		</view>
		<view :style="'height:' + statusBarHeight * 2 + 'px'" style="margin-bottom: 60rpx;"></view>
		<book-list :bookList="list"></book-list>
		<!-- 默认提示 -->
		<view v-if="list.length == 0" class="flex align-center justify-center text-secondary font"
			style="height: 200rpx;">
			暂无搜索内容
		</view>
		<view v-else-if="list.length > 10 || loadText == '没有更多了'"
			class="flex align-center justify-center font text-light-muted " style="height: 80rpx;">
			{{loadText}}
		</view>
	</view>
</template>

<script>
	import BookList from '@/components/book-list.vue';
	import {
		search
	} from '@/api/novel.js'
	export default {
		components: {
			BookList
		},
		data() {
			return {
				statusBarHeight: this.$statusBarHeight,
				page: 1,
				form: {
					keyword: '',
				},
				list: [],
				loadText: '上拉加载更多'
			}
		},
		onReachBottom() {

			if (this.loadText !== '上拉加载更多') {
				return
			}
			this.loadText = '正在加载中';
			this.page++;
			this.getSearch();

		},
		onLoad(option) {
			if (option.keyword) {
				this.form.keyword = option.keyword
				this.toSearch();
			}
		},
		methods: {
			toSearch() {
				if (this.form.keyword == '') {
					return uni.showToast({
						title: '关键词不能为空',
						icon: 'none'
					})
				}
				this.page = 1;
				this.getSearch();
			},
			getSearch() {
				search(this.page, this.form).then(res => {
					this.list = res.data.list;
					if (this.page == 1) {
						this.list = res.data.list;
					} else {
						this.list = [...this.list, ...res.data.list];
					}

					this.loadText = res.data.list.length === 10 ? '上拉加载更多' : '没有更多了';
				}).catch(err => {
					if (this.page > 1) {
						this.page--;
					}
					this.loadText = '上拉加载更多';
				})
			}
		}
	}
</script>

<style>

</style>
