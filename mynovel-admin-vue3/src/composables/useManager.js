import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { showModal, Toast } from '~/composables/until.js'

import { logout, updatepassword } from '~/api/manager.js'
// 修改密码
export function useRepassword() {

    const router = useRouter();
    const store = useStore();

    const formDrawerRef = ref(null);
    const pwdFormRef = ref(null);
    const form = reactive({
        oldpassword: '',
        password: '',
        repassword: '',
    });

    const rules = reactive({
        oldpassword: [
            {
                required: true,
                message: '旧密码不能为空',
                trigger: 'blur',
            }
        ],
        password: [
            {
                required: true,
                message: '新密码不能为空',
                trigger: 'blur',
            }
        ],
        repassword: [
            {
                required: true,
                message: '确认密码不能为空',
                trigger: 'blur',
            }
        ]
    });

    // 打开修改密码弹框
    const openFormDrawer = () => formDrawerRef.value.open();


    const onSubmitPwd = () => {
        pwdFormRef.value.validate((valid) => {
            if (!valid) {
                return false;
            }

            formDrawerRef.value.showLoading();

            updatepassword(form).then(res => {
                Toast('修改密码成功，请重新登录');

                // 清除登录信息
                store.dispatch('logout');
                router.push('/login');


            }).finally(() => {
                formDrawerRef.value.hideLoading();
            })

        })
    }

    return {
        formDrawerRef,
        pwdFormRef,
        form,
        rules,
        openFormDrawer,
        onSubmitPwd
    }
}


// 退出登录
export function useLogout() {
    const router = useRouter();
    const store = useStore();

    const onLogout = () => {
        // console.log('退出登录');
        showModal('是否要退出登录?').then(res => {
            // console.log(res);
            logout().finally(() => {
                store.dispatch('logout');
                // 跳转回登录页面
                router.push('/login')
                // 提示退出成功
                Toast('退出成功');
            })


        }).catch(err => {

        })
    }

    return {
        onLogout
    }

}