(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-my"],{"38e7":function(t,e,i){"use strict";i.r(e);var n=i("559f"),a=i("ce3e");for(var o in a)["default"].indexOf(o)<0&&function(t){i.d(e,t,(function(){return a[t]}))}(o);var c=i("f0c5"),s=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"46d719b8",null,!1,n["a"],void 0);e["default"]=s.exports},"559f":function(t,e,i){"use strict";i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{style:{height:t.statusBarHeight+"px"}}),i("v-uni-view",{staticClass:"flex align-center justify-between px-2",staticStyle:{height:"200rpx"}},[i("v-uni-view",{staticClass:"flex align-center"},[i("v-uni-image",{staticStyle:{width:"130rpx",height:"130rpx","border-radius":"50%"},attrs:{src:t.user.avatar?t.user.avatar:"../../static/images/head.png",mode:"aspectFill"}}),t.user.token?i("v-uni-text",{staticClass:"pl-2"},[t._v(t._s(t.user.nickname||t.user.username))]):i("v-uni-text",{staticClass:"pl-2",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toLogin.apply(void 0,arguments)}}},[t._v("请先登录")])],1),i("v-uni-view",{staticClass:"flex align-center justify-center rounded-circle py-1 px-2",staticStyle:{"background-color":"#ecf0f3"}},[t._v(t._s(t.user.userLevel?t.user.userLevel.name:"普通会员"))])],1),i("v-uni-view",[i("v-uni-view",{staticClass:"flex align-center justify-between px-2 text-light-black",staticStyle:{height:"100rpx"},attrs:{"hover-class":"bg-light"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.navigateTo("userinfo")}}},[i("v-uni-text",[t._v("设置")]),i("iconfont",{attrs:{iconId:"icon-iconfonti",iconColor:"text-light-black"}})],1),i("v-uni-view",{staticStyle:{height:"12rpx","background-color":"#f8f8f8"}})],1),i("v-uni-view",[i("v-uni-view",{staticClass:"flex align-center justify-between px-2 text-light-black",staticStyle:{height:"100rpx"},attrs:{"hover-class":"bg-light"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.navigateTo("password")}}},[i("v-uni-text",[t._v("修改密码")]),i("iconfont",{attrs:{iconId:"icon-iconfonti",iconColor:"text-light-black"}})],1),i("v-uni-view",{staticStyle:{height:"12rpx","background-color":"#f8f8f8"}})],1),i("v-uni-view",[i("v-uni-view",{staticClass:"flex align-center justify-between px-2 text-light-black",staticStyle:{height:"100rpx"},attrs:{"hover-class":"bg-light"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.logout.apply(void 0,arguments)}}},[i("v-uni-text",[t._v("切换账号")]),i("iconfont",{attrs:{iconId:"icon-iconfonti",iconColor:"text-light-black"}})],1),i("v-uni-view",{staticStyle:{height:"12rpx","background-color":"#f8f8f8"}})],1)],1)},a=[]},"6a93":function(t,e,i){"use strict";i("7a82");var n=i("4ea4").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("99af");var a=n(i("5530")),o=i("26cb"),c=i("7792"),s={data:function(){return{statusBarHeight:this.$statusBarHeight}},computed:(0,a.default)({},(0,o.mapState)({user:function(t){return t.user.user}})),onLoad:function(){},methods:{toLogin:function(){uni.navigateTo({url:"/pages/login/login"})},logout:function(){var t=this;this.$store.state.user.token?uni.showModal({title:"提示",content:"是否要退出登录？",success:function(e){e.confirm&&(0,c.logout)().then((function(e){t.$store.commit("user/logout")}))}}):uni.navigateTo({url:"/pages/login/login"})},navigateTo:function(t){uni.navigateTo({url:"/pages/".concat(t,"/").concat(t)})}}};e.default=s},ce3e:function(t,e,i){"use strict";i.r(e);var n=i("6a93"),a=i.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a}}]);