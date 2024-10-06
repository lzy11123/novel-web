import Vue from 'vue'
import Vuex from 'vuex'

import audio from './audio/audio.js'
import user from './user/user.js'
Vue.use(Vuex);

const store = new Vuex.Store({
	modules: {
		audio,
		user
	}
})

export default store
