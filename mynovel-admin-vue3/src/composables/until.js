import { ElNotification, ElMessageBox } from 'element-plus'
import nprogress from 'nprogress';
// 消息提示  dangerouslyUseHTMLString是否渲染html片段
export function Toast(message, type = "success", dangerouslyUseHTMLString = true, duration = 2000,) {
    ElNotification({
        message,
        type,
        dangerouslyUseHTMLString,
        duration,
    })
}

// 消息确认提示框
export function showModal(content = "内容提示", type = "warning", title = "") {
    return ElMessageBox.confirm(
        content,
        title,
        {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: type,
        }
    )
}



// 弹出输入框
export function showPrompt(tip, value, title = "") {
    return ElMessageBox.prompt(tip, title, {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputValue: value
    })
}

// 显示全屏加载loading条
export function showFullLoading() {
    nprogress.start();
}

// 隐藏全屏加载loading条
export function hideFullLoading() {
    nprogress.done();
}

// 将query对象转成url参数
export function queryParams(query) {

    // console.log(query)

    let q = [];
    for (let key in query) {
        if (query[key]) {
            q.push(`${key}=${encodeURIComponent(query[key])}`);
        }
    }
    let r = q.join('&');



    r = r ? '?' + r : '';
    // console.log(r);
    return r;
}


// 上移
export function useArrayMoveUp(arr, index) {
    swapArray(arr, index, index - 1)
}

// 下移
export function useArrayMoveDown(arr, index) {
    swapArray(arr, index, index + 1)
}
// 交换位置
function swapArray(arr, index1, index2) {
    arr[index1] = arr.splice(index2, 1, arr[index1])[0];
    return arr;
}

// sku排列算法
export function cartesianProductOf() {
    return Array.prototype.reduce.call(arguments, function (a, b) {
        var ret = [];
        a.forEach(function (a) {
            b.forEach(function (b) {
                ret.push(a.concat([b]))
            })
        })
        return ret;
    }, [
        []
    ])
}

const tokenName = 'ZTOKEN'
const tokenTime = 'ZTOKEN_TIME'
export const setToken = (token) => {
    localStorage.setItem(tokenName, token)
}

export const getToken = () => {
    return localStorage.getItem(tokenName)
}

export const removeToken = () => {
    localStorage.removeItem(tokenName)
}

export const setTokenTime = (time) => {
    localStorage.setItem(tokenTime, time)
}

export const getTokenTime = () => {
    return localStorage.getItem(tokenTime)
}

export const removeTokenTime = () => {
    localStorage.removeItem(tokenTime)
}
