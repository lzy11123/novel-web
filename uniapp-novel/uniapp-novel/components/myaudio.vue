<template>
	<view class="fixed-bottom rounded mx-2 mb-1 " style="height: 180rpx;background-color: #003366;opacity: 0.8;"
		:style="{bottom:`${windowBottom}px`}">
		<view class="flex align-center justify-center font" style="color: #fff;height: 65rpx;">
			<!-- 总时长 -->
			<view class="">
				{{durationTime | formatMusicTime}}
			</view>
			<!-- 进度条 -->
			<view class="" style="width: 500rpx;">
				<slider block-size="16" activeColor="#007AFF" block-color="#fff" :max="durationTime "
					:value="currentTime" @change="slideToPlay" @changing="slideToPlay" />
			</view>
			<!-- 播放时刻 -->
			<view class="">
				{{currentTime | formatMusicTime}}
			</view>
		</view>
		<view class="flex align-center justify-between mx-2" style="height: 95rpx;">
			<!-- 音频相关信息 -->
			<view class="text-white" style="font-size: 28rpx;" @click="toDetalPage">
				<view class="">
					歌曲-{{audioName}}
				</view>
				<view class="">
					演唱-{{singerName}}
				</view>

			</view>
			<!-- 播放按钮控制 -->
			<view class="flex align-center mt-auto">
				<view class="animate__animated" hover-class="animate__pulse">
					<iconfont iconId="icon-shangyishou" iconColor="text-white" :iconSize="75" @click="preOrNext('pre')">
					</iconfont>
				</view>
				<iconfont class="mx-1" :iconId="playState ? 'icon-bofang' : 'icon-ziyuan'" iconColor="text-white"
					:iconSize="75" @click="playOrPause">
				</iconfont>
				<view class="animate__animated" hover-class="animate__pulse">
					<iconfont iconId="icon-xiayishou" iconColor="text-white" :iconSize="75" @click="preOrNext('next')">
					</iconfont>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapGetters,
		mapMutations,
		mapActions
	} from 'vuex';
	import unit from '../common/unit.js';
	export default {
		data() {
			return {
				windowBottom: uni.getSystemInfoSync().windowBottom
			}
		},
		filters: {
			...unit,
		},
		computed: {
			...mapState({
				playState: state => state.audio.playState,
				durationTime: state => state.audio.durationTime,
				currentTime: state => state.audio.currentTime,
			}),
			...mapGetters(['audioName', 'singerName'])
		},
		methods: {
			...mapMutations(['destruction']),
			...mapActions(['init', 'playOrPause', 'preOrNext', 'slideToPlay']),

			// 跳转音乐详情页
			toDetalPage() {
				uni.navigateTo({
					url: '/pages/music-detail/music-detail',
				})
			}
		},
		mounted() {
			this.init();
		},
		destroyed() {
			this.destruction();
		}
	}
</script>

<style lang="less">

</style>
