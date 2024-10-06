// 音频资源
let music = [{
	id: 1,
	name: '痛みを',
	src: 'http://blog.kplan.top/uploads/file/640010fe6ac02.mp3',
	singer: {
		name: '花谱',
		synopsis: '2018年10月から活動を開始し2周年を迎える花譜の新たな作品が誕生。'
	}
}, {
	id: 2,
	name: '鶵鸟',
	src: 'http://blog.kplan.top/uploads/file/63b8ff8094b08.mp3',
	singer: {
		name: '花谱',
		synopsis: '2018年10月から活動を開始し2周年を迎える花譜の新たな作品が誕生。'
	}
}];


let audio;
let timeout;
export default {
	state: {
		playState: false, // 暂停标识 
		currentPlayIndex: 0, // 当前播放歌曲标识
		durationTime: 0, // 音频总时长
		currentTime: 0, // 音频当前播放时间
		audioList: [],
	},
	getters: {
		// 音频名称
		audioName(state) {
			let currentIndex = state.currentPlayIndex;
			return music[currentIndex].name;
		},
		// 歌手名称
		singerName(state) {
			let currentIndex = state.currentPlayIndex;
			return music[currentIndex].singer.name;
		},
		singerIntro(state) {
			let currentIndex = state.currentPlayIndex;
			return music[currentIndex].singer.synopsis;
		}
	},
	mutations: {
		// 销毁
		destruction() {
			audio.offPlay();
			audio.offPause();
			audio.offStop();
			audio.offEnded();
			audio.offError();

		},
		// 改变播放标识
		changePlayState(state, boolean) {
			state.playState = boolean;
		},
		// 获取音频总时长
		getDurationTime(state, time) {
			// console.log(time);
			state.durationTime = time;
		},
		// 播放
		audioPlay(state) {
			let currentIndex = state.currentPlayIndex;
			audio.src = music[currentIndex].src;
			audio.play();
		},
		// 时间跳转
		audioSeel(state, pos) {
			audio.seek(pos);
		},
		// 暂停
		audioPause() {
			audio.pause();
		},
		// 停止
		audioStop() {
			audio.stop();
		},
		// 改变播放标识
		changePlayIndex(state, index) {
			state.currentPlayIndex = index;

		},
		// 改变播放时间(暂停时)
		changeCurrentTime(state, time) {
			state.currentTime = time;
		},
		// 获取音频列表
		getAudioList(state, audioList) {
			for (let item of audioList) {
				state.audioList.push({
					id: item.id,
					audioName: item.name,
					singerName: item.singer.name,
					playStatus: 0, // -1暂停 0停止 1 播放
				})
			}
		}
	},
	actions: {
		// 初始化
		init({
			state,
			commit,
			dispatch
		}) {

			if (audio) {
				return
			}

			commit('getAudioList', music);

			// 实例化音频对象
			let currentIndex = state.currentPlayIndex;
			audio = uni.createInnerAudioContext();
			audio.src = music ? music[currentIndex].src : '';
			// 监听
			//音频播放事件
			audio.onCanplay(() => {
				// state.durationTime = audio.duration;
		
				commit('getDurationTime', audio.duration);
				// #ifdef MP
				commit('getDurationTime', audio.duration);
				// #endif
			})
			// 开始播放
			audio.onPlay(() => {
				state.playState = true;

				commit('changePlayState', true);

				// console.log('开始播放')
			})
			// 暂停播放
			audio.onPause(() => {
				commit('changePlayState', false);
				// console.log('暂停播放')
			})
			// 停止播放
			audio.onStop(() => {
				commit('changePlayState', false);
				// console.log('停止播放')
			})

			// 播放结束
			audio.onEnded(() => {
				commit('changePlayState', false);
				dispatch('preOrNext', 'next');
				// console.log('播放结束')
			})
			// 播放错误
			audio.onError((res) => {
				console.log(res);
				commit('changePlayState', false);
				// console.log('播放错误');
			})
			// 时间更新
			audio.onTimeUpdate(() => {
				commit('changeCurrentTime', audio.currentTime);
			})
		},
		// 播放与暂停
		playOrPause({
			state,
			commit
		}) {
			if (!state.playState) {
				commit('audioPlay')
			} else {
				commit('audioPause')
			}
			// 从暂时后的时间开始播放
			commit('audioSeel', state.currentTime)
		},
		// 切歌
		preOrNext({
			state,
			commit
		}, type) {
			commit('audioStop');
			let currentIndex = state.currentPlayIndex;
			let lastIndex = music.length - 1;
			switch (type) {
				case 'pre':
					currentIndex === 0 ? commit('changePlayIndex', lastIndex) : commit('changePlayIndex',
						currentIndex - 1);
					break;
				case 'next':
					currentIndex === lastIndex ? commit('changePlayIndex', 0) : commit('changePlayIndex', currentIndex +
						1);
					break;
				default:
					break;
			}
			commit('audioPlay');
		},
		// 拖动时间
		slideToPlay({
			state,
			commit
		}, e) {
			let {
				value,
			} = e.detail;
			// 跳转到选中时间
			commit('audioSeel', value)
			if (!state.playState) {
				clearTimeout(timeout);
				timeout = setTimeout(() => {
					commit('changeCurrentTime', value)
				}, 200);
			}

		},
		// 列表选择播放
		selectPlay({
			state,
			commit
		}, id) {

			let index = music.findIndex(item => item.id == id);

			if (index == state.currentPlayIndex) {
				if (state.playState) {
					commit('audioPause');
				} else {
					commit('audioPlay');
				}
			} else {
				commit('changePlayIndex', index);
				commit('audioPlay');
			}

		}
	}
}
