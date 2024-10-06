<template>
    <el-row class="login-container">
        <el-col :lg="16" :md="12" class="login-left">
            <div>
                <div class="">
                    WELCOME
                </div>
                <!-- <div class="">后台管理系统</div> -->
                <div>

                </div>
            </div>
        </el-col>
        <el-col :lg="8" :md="12" class="login-right">
            <h2 class="title">欢迎回来</h2>
            <div class="info">
                <span class="line"></span>
                <span>账号密码登陆</span>
                <span class="line"></span>
            </div>
            <el-form ref="loginForm" :rules="rules" :model="form" class="w-[250px]">
                <el-form-item prop="username">
                    <el-input v-model="form.username" placeholder="请输入用户名">
                        <template #prefix>
                            <el-icon>
                                <User />
                            </el-icon>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input type="password" show-password v-model="form.password" placeholder="请输入密码">
                        <template #prefix>
                            <el-icon>
                                <Lock />
                            </el-icon>
                        </template>

                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :loading="loading" class="w-[250px]" round color="#626aef" type="primary"
                        @click="onSubmit">登录
                    </el-button>
                </el-form-item>
            </el-form>
        </el-col>
    </el-row>
</template>

<script  setup>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue'

import { useRouter } from 'vue-router';
import { useStore } from 'vuex'
// do not use same name with ref

import { Toast } from '~/composables/until.js'

const router = useRouter();
const store = useStore();
const loginForm = ref(null);
const loading = ref(false);
const form = reactive({
    username: '',
    password: '',
})
const rules = reactive({
    username: [
        { required: true, message: '请输入用户名', trigger: 'blur' },
        { min: 3, max: 20, message: '用户名长度为3-20字符', trigger: 'blur' },
    ],
    password: [
        { required: true, message: '请输入密码', trigger: 'blur' }
    ]
})
// 登录
const onSubmit = () => {
    loginForm.value.validate((valid) => {
        // console.log(valid);
        if (!valid) {
            return false
        }

        loading.value = true;

        store.dispatch('login', form).then(res => {
            // console.log(res);
            // 提示成功
            Toast('登录成功')
            // 跳转到后台首页
            router.push('/')
        }).finally(() => {
            loading.value = false;
        })

    })

}
// 监听回车事件
function onKeyUp(e) {
    // console.log(e);
    if (e.key == 'Enter') {
        onSubmit();
    }
}

onMounted(() => {
    // 添加键盘监听
    document.addEventListener('keyup', onKeyUp)
})

onBeforeUnmount(() => {
    document.removeEventListener('keyup', onKeyUp)
})

</script>

<style scoped>
.login-container {
    @apply min-h-screen bg-indigo-500;
}

.login-container .login-left,
.login-container .login-right {
    @apply flex items-center justify-center;
}

.login-container .login-left {
    @apply flex-col text-center;
}

.login-container .login-right {
    @apply bg-light-50 flex-col;
}

.login-left>div>div:first-child {
    @apply font-bold text-5xl text-light-50 mb-4;
}

.login-left>div>div:nth-child(2) {
    @apply text-gray-200 text-sm;
}

.login-right .title {
    @apply text-3xl text-gray-800;
}

.login-right .info {
    @apply flex items-center justify-center space-x-2 my-5 text-gray-300;
}

.login-right .line {
    @apply h-[1px] w-16 bg-gray-200;
}
</style>