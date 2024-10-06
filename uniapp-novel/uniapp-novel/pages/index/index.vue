<template>
	<view class="">
		<view :style="'height:' + statusBarHeight + 'px'"></view>
		<search-box></search-box>
		<swiper-slide :list="swiper"></swiper-slide>
		<function-cate :cateList="cateList"></function-cate>
		<recommond :readBooks="readBooks"></recommond>

		<block v-for="(item,index) in bookResources" :key="index">
			<list-title :item="{name:item.name,id:item.id}">
				<template #title>{{item.name}}</template>
			</list-title>
			<book-list :bookList="item.books"></book-list>
		</block>
	</view>
</template>

<script>
	import searchBox from '@/components/search-box.vue';
	import swiperSlide from '@/components/swiper-slide.vue';
	import functionCate from '@/components/function-cate.vue';
	import listTitle from '@/components/list-title.vue';
	import recommond from '@/components/recommond.vue';
	import bookList from '@/components/book-list.vue';

	import {
		getHomeData
	} from '@/api/home.js'

	export default {
		components: {
			searchBox, // 搜索组件
			swiperSlide, // 轮播图组件
			functionCate, // 分类组件
			listTitle, // 列表头部
			recommond, // 推荐组件
			bookList, // 列表
		},
		data() {
			return {
				statusBarHeight: this.$statusBarHeight,
				swiper: [],
				cateList: [{
						src: '/static/images/cate1.png',
						title: '热门推荐',
						path: '/pages/hot-list/hot-list',
						type: 'navigateTo'
					},
					{
						src: '/static/images/cate2.png',
						title: '最新上架',
						path: '/pages/new-list/new-list',
						type: 'navigateTo'
					}, {
						src: '/static/images/cate3.png',
						title: '最新排行',
						path: '/pages/read-list/read-list',
						type: 'navigateTo'
					}, {
						src: '/static/images/cate4.png',
						title: '全部分类',
						path: '/pages/cate/cate',
						type: 'switchTab'

					}
				],
				readBooks: [],
				bookResources: []
			}
		},
		onLoad() {
			this.getData()
		},
		methods: {
			// 获取数据
			getData() {
				getHomeData().then(res => {
					this.swiper = res.data.banner.map(item => {
						item.src = item.cover;
						return item;
					});
					this.readBooks = res.data.tolike;
					this.bookResources = res.data.list;
				})
			},

		}
	}
</script>

<style>

</style>
