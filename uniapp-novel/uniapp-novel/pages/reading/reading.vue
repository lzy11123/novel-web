<template>
	<view class="read-page">
		<view :class="curTheme" :style="{height: `${statusBarHeight};`}" class="cal"></view>
		<!-- 设置开始 -->
		<!-- 设置头部 -->
		<view :class="curTheme" class="fixed-top  shadow animate__animated animate__slideInDown" v-if="setStatus">
			<view :style="{height: `${statusBarHeight}`}"></view>
			<view class="flex align-center" style="height: 80rpx;">

				<!-- #ifndef MP -->
				<iconfont iconId="icon-jiantou-copy" class="px-1" @click="quit"></iconfont>
				<!-- #endif -->
				<text class="pl-2">{{novalName}}</text>
				<text class="flex-1 text-ellipsis px-2 font-sm">章节：{{curChapterTitle}}</text>
			</view>
		</view>
		<!-- 设置底部部分 -->
		<view :class="curTheme" class="fixed-bottom  shadow flex align-center font animate__animated animate__slideInUp"
			style="height: 200rpx;" v-if="setStatus">
			<view class="flex-1 flex flex-column align-center justify-center" style="height: 100%;">
				<iconfont iconId="icon-xueyuan-mulu" iconSize="55" @click="changeCategoryStatus(true)"></iconfont>
				<view class="" @click="changeCategoryStatus(true)">
					目录
				</view>
			</view>
			<view class="flex-1 flex flex-column align-center justify-center" style="height: 100%;">
				<iconfont iconId="icon-yanjing" iconSize="55" @click="isNight"></iconfont>
				<view class="" @click="isNight">
					夜间模式
				</view>
			</view>
			<view class="flex-1 flex flex-column align-center justify-center" style="height: 100%;">
				<iconfont iconId="icon-ziti1" iconSize="55" @click="changeTypeFaceState(true)"></iconfont>
				<view class="" @click="changeTypeFaceState(true)">
					字体
				</view>
			</view>
			<view class="flex-1 flex flex-column align-center justify-center" style="height: 100%;">
				<iconfont iconId="icon-diqiuhuanqiu" iconSize="55" @click="changeMoreStatus(true)"></iconfont>
				<view class="" @click="changeMoreStatus(true)">
					更多
				</view>
			</view>
		</view>
		<!-- 设置结束 -->


		<!-- 目录开始 -->
		<uni-drawer ref="drawer" @close="changeCategoryStatus(false)">
			<view :class="curTheme" :style="{height: `${statusBarHeight};`}"></view>
			<view :class="curTheme" class="flex align-center justify-center" style="height: 80rpx;">
				章节选择
			</view>
			<scroll-view :class="curTheme" scroll-y="true" :style="{height:`${calHeight - 80}rpx`}">
				<block v-for="(item,index) in chapterCatalog" :key="item.id">
					<view class="px-1 py-2 font text-ellipsis border-bottom"
						:class="index === chapterIndex ? 'curChapter':''" @click="toPointChapter(item.id)">
						{{item.name}}
					</view>
				</block>
			</scroll-view>
		</uni-drawer>
		<!-- 目录结束 -->
		<!-- 字体设置开始 -->
		<view :class="curTheme" class="fixed-bottom  shadow  px-3 pt-2" style="height:180rpx" v-if="typeFaceState">
			<view class="flex align-center">
				字体:
				<slider class="flex-1" min="20" max="50" :value="myFontSize" block-size="16" activeColor="#34495e"
					backgroundColor="#ecf1f0" @change="changeFontSize" @changing="changeFontSize">
				</slider>
			</view>
			<view class="flex align-center">
				间距:
				<slider class="flex-1" min="20" max="100" :value="myLineHeight" block-size="16" activeColor="#34495e"
					backgroundColor="#ecf1f0" @change="changeLineHeight" @changing="changeLineHeight">
				</slider>
			</view>
		</view>
		<!-- 字体设置结束 -->

		<!-- 更多设置开始 -->
		<view :class="curTheme" v-if="moreStatus"
			class="fixed-bottom  shadow  px-3 pt-2 flex flex-column justify-center" style="height:250rpx">
			<!-- #ifdef APP-PLUS -->
			<view class="flex align-center">
				亮度:
				<slider class="flex-1" :value="brightNess" min="0" max="100" block-size="16" activeColor="#34495e"
					backgroundColor="#ecf1f0" @change="setBrightNess" @changing="setBrightNess">
				</slider>
			</view>
			<!-- #endif -->

			<view class="flex ">
				<block v-for="item in themes" :key="item.id">
					<view class="flex-1 mx-1" @click="changeThemeIndex(item.id)">
						<view class="py-4 border rounded" :class="item.name"></view>
						<view class="text-center font-sm mt-1">
							{{item.title}}
						</view>
					</view>
				</block>
			</view>
		</view>
		<!-- 更多设置结束 -->


		<!-- 文本开始 -->
		<view class="px-2 position-relative" :class="curTheme"
			:style="{fontSize:`${myFontSize}rpx`,lineHeight: `${myLineHeight}rpx`}" @click="changeSetStatuts"
			@touchstart="start" @touchend="end">
			<rich-text :nodes="testChapter"></rich-text>
			<view class="p-2 mt-3 flex">
				<view class="flex-1 text-center" @click.stop="nextOrPrev(100)">
					上一章
				</view>
				<view class="flex-1 text-center" @click.stop="nextOrPrev(-100)">
					下一章
				</view>
			</view>
		</view>
		<!-- 文本结束 -->
	</view>
</template>

<script>
	import $u from '@/common/unit.js';
	import htmlParser from '@/common/html-parser.js';
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue'
	import uniDrawer from '@/components/uni-drawer/uni-drawer.vue'
	import {
		getChapterInfo,
		getNovelChapter
	} from '@/api/novelDetail.js'
	export default {
		components: {
			uniLoadMore,
			uniDrawer
		},
		data() {
			return {
				scrollTop: 0,
				pageX: 0,
				chapterId: 0,
				setStatus: false,
				statusBarHeight: this.$statusBarHeight,
				novalName: '', // 小说名称
				chapterCatalog: [], // 小说章节
				calHeight: 0,
				testChapter: '',
				chapterIndex: 0, // 当前的章节标识
				typeFaceState: false,
				myFontSize: uni.getStorageSync('myFontSize') ? uni.getStorageSync('myFontSize') : 32,
				myLineHeight: uni.getStorageSync('myLineHeight') ? uni.getStorageSync('myLineHeight') : 45,
				themes: [{
					id: 1,
					name: 'blueTheme',
					title: '天蓝'
				}, {
					id: 2,
					name: 'eyeHelpTheme',
					title: '护眼'
				}, {
					id: 3,
					name: 'lightGretTheme',
					title: '淡灰'
				}, {
					id: 4,
					name: 'morningTheme',
					title: '早晨'
				}, {
					id: 5,
					name: 'nightTheme',
					title: '夜间'
				}],
				brightNess: 0, // 亮度
				moreStatus: false, // 更多设置
				themeIndex: uni.getStorageSync('themeIndex') ? uni.getStorageSync('themeIndex') : 3,
			}
		},
		onLoad(option) {
			if (option.chapterId) {
				this.chapterId = option.chapterId
				this.getChapterList();
				this.getData();
				// this.init(option.chapterId)
			}

		},
		mounted() {
			// 获取屏幕高度，并减去固定高度&转化为rpx
			$u.calSurplusHeight({
				pageID: this,
				pos: 'cal',
				success: val => this.calHeight = val
			})
		},
		computed: {
			curChapterTitle() {
				return this.chapterCatalog[this.chapterIndex].name
			},
			// 当前主题
			curTheme() {
				return this.themes[this.themeIndex].name
			}
		},
		created() {
			// 初始进入屏幕的高度
			// #ifndef MP-WEIXIN
			let height = $u.getSystemHeight(true) - $u.Torpx(this.statusBarHeight);
			// #endif
			// #ifdef MP-WEIXIN
			let height = $u.getSystemHeight(true) - $u.Torpx(this.statusBarHeight - 50);
			// #endif
			this.calHeight = Math.floor(height)
			// #ifdef APP-PLUS
			// 获取屏幕亮度
			this.getBrightNess();
			// #endif
		},
		methods: {
			getData() {
				getChapterInfo(this.chapterId).then(res => {
					// console.log(res);
					this.testChapter = htmlParser(res.data.chapter.content);
					this.$nextTick(() => {
						uni.pageScrollTo({
							scrollTop: 0,
							duration: 0,
						})
					})
				})
			},
			getChapterList() {
				getNovelChapter(this.chapterId).then(res => {
					this.chapterCatalog = res.data.list;
					this.novalName = res.data.novel.name;
				})
			},
			quit() {
				uni.navigateBack({
					delta: 1,
				});
			},

			// 改变设置状态
			changeSetStatuts() {
				if (!this.setStatus) {
					if (this.typeFaceState || this.moreStatus) {
						this.changeTypeFaceState(false);
						this.changeMoreStatus(false);
						return;
					}
				}
				this.setStatus = !this.setStatus;
			},
			// 改变章节标识
			changeChapterIndex(index) {
				this.chapterIndex = index;
				this.chapterId = this.chapterCatalog[this.chapterIndex].id;
				this.getData();
			},
			// 滑动
			start(e) {
				this.pageX = e.changedTouches[0].pageX
			},
			end(e) {
				const endX = e.changedTouches[0].pageX - this.pageX;
				this.nextOrPrev(endX)
			},
			nextOrPrev(endX) {

				// 右滑
				if (endX < -70) {
					if (this.chapterIndex != this.chapterCatalog.length - 1) {
						this.chapterIndex++;
						this.chapterId = this.chapterCatalog[this.chapterIndex].id;
						this.getData();
					} else {
						uni.showToast({
							title: '已经是最后一章了',
							icon: 'none'
						})
					}

					// 左滑
				} else if (endX > 70) {
					if (this.chapterIndex != 0) {
						this.chapterIndex--;
						this.chapterId = this.chapterCatalog[this.chapterIndex].id;
						this.getData();
					} else {
						uni.showToast({
							title: '已经是第一章了',
							icon: 'none'
						})
					}

				}
			},
			onScroll(e) {
				console.log(e)
			},
			//打开目录
			changeCategoryStatus(Bol) {

				if (Bol) {
					this.changeSetStatuts()
					this.$refs.drawer.open()
				} else {
					this.$refs.drawer.close()
				}

			},
			// 点击木马章节跳转到对应章节
			toPointChapter(id) {
				let curIndex = this.chapterCatalog.findIndex(item => item.id === id)
				if (this.changeChapterIndex == curIndex) return;
				this.changeChapterIndex(curIndex)
				this.$refs.drawer.close()
			},
			changeTypeFaceState(Bol) {
				this.typeFaceState = Bol;
				if (this.typeFaceState) {
					this.changeSetStatuts()
				}
			},
			//改变字体大小
			changeFontSize(e) {
				// console.log(e)
				this.myFontSize = e.detail.value
				uni.setStorageSync('myFontSize', this.myFontSize)
			},
			// 改变行高
			changeLineHeight(e) {
				this.myLineHeight = e.detail.value
				uni.setStorageSync('myLineHeight', this.myLineHeight)
			},
			// #ifdef APP-PLUS
			// 获取亮度
			getBrightNess() {
				uni.setScreenBrightness({
					success(res) {
						// console.log(res);
						this.brightNess = Math.floor((res.value)) / 8 * 100;
					}
				})
			},
			setBrightNess(e) {
				// console.log(e)
				let newVal = e.detail.value
				this.brightNess = newVal
				uni.setScreenBrightness({
					value: newVal / 8 * 100
				})
			},
			// #endif
			// 更多设置
			changeMoreStatus(Bol) {
				this.moreStatus = Bol
				if (this.moreStatus) {
					this.changeSetStatuts()
				}
			},
			// 更改主题标识
			changeThemeIndex(id) {


				let curIndex = this.themes.findIndex(item => item.id === id);

				if (curIndex != -1) {
					this.themeIndex = curIndex
					uni.setStorageSync('themeIndex', this.themeIndex)
				}
			},
			// 切换夜间模式
			isNight() {

				this.themeIndex != 4 ? this.themeIndex = 4 : this.themeIndex = 3

				uni.setStorageSync('themeIndex', this.themeIndex)
			},
		}
	}
</script>

<style scoped>
	/* .read-page {
		position: fixed;
		top: 0;
		left: 0;
		
		width: 100%;
		height: 100%;
		overflow: auto;
	} */

	.curChapter {
		background-color: #8395a7;
		color: #fff;
	}
</style>