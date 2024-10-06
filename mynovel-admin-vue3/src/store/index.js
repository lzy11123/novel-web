import { createStore } from 'vuex'
import { setToken, removeToken } from '~/composables/until.js'

import { login, getinfo } from '~/api/manager.js'

const store = createStore({
    state() {
        return {
            // 用户信息
            user: {},
            // 侧边宽度
            asideWidth: '200px',
            //菜单
            menus: [],
            // 规则名称
            ruleNames: [],

        }
    },
    mutations: {
        // 设置用户信息
        SET_USERINFO(state, user) {
            // console.log(user);
            state.user = user
        },
        // 展开/收起侧边
        handleAsideWidth(state) {
            state.asideWidth = state.asideWidth == '200px' ? '64px' : '200px'
        },
        // 设置菜单
        SET_MENUS(state, menus) {
            state.menus = menus;
        },
        // 设置规则名称
        SET_RULENAMES(state, ruleNames) {
            state.ruleNames = ruleNames;
        }
    },
    actions: {
        login({ commit }, { username, password }) {
            return new Promise((resolve, reject) => {
                login(username, password).then(res => {
                    // console.log(res);
                    setToken(res.token);
                    // commit('SET_USERINFO', res)
                    // setTokenTime(res.result.expires)
                    resolve(res);
                }).catch(err => {
                    reject(err);
                })
            })
        },
        logout({ commit }) {
            removeToken();
            // 清除用户管理状态 vuex
            commit('SET_USERINFO', {})
            commit('SET_MENUS', [])
        },
        // 获取当前登录用户信息
        getManagerInfo({ commit }) {
            return new Promise((resolve, reject) => {
                getinfo().then(res => {
                    // console.log(res);
                    // commit('SET_USERINFO', res)

                    commit('SET_USERINFO', res)
                    commit('SET_MENUS', res.menus);
                    commit('SET_RULENAMES', res.ruleNames);

                    resolve(res);
                }).catch(err => {
                    reject(err);
                })
            })
        },

        setMenus({ commit }, data) {
            commit('SET_MENUS', data)
        }
    }
})

export default store