<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
			// 初始化是否登录了
			this.$store.commit('user/initUser')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/*每个页面公共css */
	/* 自定义css库 */
	@import url("./common/free.css");
	/* 图标库 */
	@import url("./common/iconfont.css");
	/* css动画库 */
	@import url("./common/animate.css");
	/* 自定义css动画 */
	@import url("./common/my-animated.css");

	/* 主题样式 */
	@import url("./common/theme.css");

	view,
	text {
		box-sizing: border-box;
	}

	.text-main {
		color: #003366;
	}

	.bg-main {
		background-color: #003366;
	}

	.bg-main-hover {
		background-color: #003366 ;
		opacity: 0.8;
	}

	/* 
	#CCCCCC
	#003366
	#99CCFF 
	*/
</style>
