<template>
	<view>
		<!-- #ifndef MP -->
		<page-title>{{cate}}</page-title>
		<!-- #endif -->
		<book-list :bookList="loadBookList"></book-list>
		<uni-load-more :status="loadMoreStatus"></uni-load-more>
	</view>
</template>

<script>
	import pageTitle from '@/components/page-title.vue';
	import bookList from '@/components/book-list.vue';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue'
	import {
		getCategoryNovel
	} from '@/api/novel.js'
	export default {
		components: {
			pageTitle,
			bookList,
			uniLoadMore
		},
		data() {
			return {
				loadText: '上拉加载更多',
				loadMoreStatus: 'loading',
				loadBookList: [],
				page: 1,
				novel_id: 0,
				cate: '',
			}
		},
		onLoad(option) {
			if (option) {
				this.cate = option.cate;
				this.novel_id = option.id;
				uni.setNavigationBarTitle({
					title: this.cate,
				})
				this.initLoadMore();
			}

		},
		onReachBottom() {
			this.loadMore();
		},
		methods: {
			// 初始化加载
			initLoadMore() {
				getCategoryNovel(this.novel_id, this.page).then(res => {
					// console.log(res);
					if (this.page == 1) {
						this.loadBookList = res.data.list;
					} else {
						this.loadBookList = [...this.loadBookList, ...res.data.list];
					}

					this.loadText = res.data.list.length >= 10 ? '上拉加载更多' : '没有更多了';
					this.loadMoreStatus = res.data.list.length >= 10 ? '' : 'noMore';
				}).catch(err => {
					if (this.page > 1) {
						this.page--;
					}
					this.loadText = '上拉加载更多';
				})

			},
			// 上拉加载
			loadMore() {
				switch (this.loadText) {
					case '正在加载中':
						return
						break;
					case '没有更多了':
						this.loadMoreStatus = 'noMore';
						break;
					case '上拉加载更多':
						this.loadText = '正在加载中';
						this.loadMoreStatus = 'loading';
						this.page++;
						this.initLoadMore();
						break;
				}



			}
		},



	}
</script>

<style>

</style>
