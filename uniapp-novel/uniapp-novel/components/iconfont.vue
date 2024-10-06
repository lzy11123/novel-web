<template>
	<!-- 图标组件 -->
	<text class="iconfont" :class="[iconId,iconColor]" :style="{fontSize:`${iconSize}rpx`}"
		@click.stop="$emit('click')"></text>
</template>

<script>
	export default {
		props: {
			iconId: {
				type: String,
				default: '',
			},
			iconColor: {
				type: String,
				default: 'text-dark',
			},
			iconSize: {
				type: [Number,String],
				default: 44,
			}

		}
	}
</script>

<style>

</style>
