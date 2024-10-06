<?php
// 应用公共文件

/**
 * 获取用户信息（token校验）
 *
 * @param array $param 参数配置(tag,token,password)
 * @return void
 */
function cms_getUser(array $param)
{

    $tag = getValByKey('tag', $param, 'manager');
    $token = getValByKey('token', $param);
    $password = getValByKey('password', $param);

    $user = \think\facade\Cache::store(config('cms.' . $tag . '.token.store'))->get($tag . '_' . $token);

    if (!$password) unset($user['password']);
    return $user;
}

// 成功返回
function showSuccess($data = [], $msg = 'ok', $code = 200)
{
    return json([
        'msg' => $msg,
        'data' => $data,
    ], $code);
}

// 失败返回
function showError($msg = 'fail', $code = 400)
{
    return json([
        'msg' => $msg,
    ], $code);
}

// 抛出异常
function ApiException($msg = '请求错误', $errorCode = 20000, $statusCode = 400)
{
    
    abort($errorCode, $msg, [
        'statusCode' => $statusCode
    ]);
}

// 获取数组指定key的value
function getValByKey($key, $arr, $default = false)
{
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}


/**
 * 登录（设置并存储token）
 *
 * @param array $param  参数配置(data,password,tag,expire)
 * @return void
 */
function cms_login(array $param)
{
    $data = getValByKey('data', $param);

    // dump($param);


    if (!$data) return false;


    // 标签分组
    $tag = getValByKey('tag', $param, 'manager');

    //是否返回密码
    $password = getValByKey('password', $param);

    // 登录有效时间 0为永久
    $expire = getValByKey('expire', $param, 0);

    $CacheClass = \think\facade\Cache::store(config('cms.' . $tag . '.token.store'));

    // 生成唯一token
    $token = sha1(md5(uniqid(md5(microtime(true)), true)));

    // 拿到当前用户数据
    $user = is_object($data) ? $data->toArray() : $data;



    // 获取之前并删除token （防止重复登录）
    // $token = getValByKey('token', $param);

    // dump($token);

    $beforeToken = $CacheClass->get($tag . '_' . $user['id']);

    // 删除之前token对应的用户信息
    if ($beforeToken) {

        cms_logout([
            'token' => $beforeToken,
            'tag' => $tag
        ]);
    }
    // dump($beforeToken);
    // 存储token  - 用户数据
    $CacheClass->set($tag . '_' . $token, $user, $expire);

    // 存储用户id - token
    $CacheClass->set($tag . '_' . $user['id'], $token, $expire);

    // 隐藏密码
    if ($password) unset($user['password']);


    // 返回token
    $user['token'] = $token;
    return $user;
}

/**
 * 退出登录（删除token）
 *
 * @param array $param 参数配置 (tag,token,password)
 * @return void
 */
function cms_logout(array $param)
{
    $tag = getValByKey('tag', $param, 'manager');
    $token = getValByKey('token', $param);

    // 获取并删除缓存
    $user = \think\facade\Cache::store(config('cms.' . $tag . '.token.store'))->pull($tag . '_' . $token);

    if (!empty($user)) \think\facade\Cache::store(config('cms.' . $tag . '.token.store'))->pull($tag . '_' . $user['id']);

    // 去除密码
    unset($user['password']);

    return $user;
}


/**
 * 数据集组合分类树(一维数组)
 * @param     cate 分类查询结果集
 * @param     html 格式串
 * @return    array
 */
function list_to_tree($array, $field = 'pid', $pid = 0, $level = 0)
{
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value) {
        if ($value[$field] == $pid) {
            $value['level'] = $level;
            $list[] = $value;
            unset($array[$key]);
            list_to_tree($array, $field, $value['id'], $level + 1);
        }
    }
    return $list;
}

/**
 * 数据集组合分类树(多维数组)
 * @param     cate 分类结果集
 * @param     child 子树
 * @return    array
 */
function list_to_tree2($cate, $field = 'pid', $child = 'child', $pid = 0, $callback = false)
{
    if (!is_array($cate)) return [];
    $arr = [];
    foreach ($cate as $v) {
        $extra = true;
        if (is_callable($callback)) {
            $extra = $callback($v);
        }
        if ($v[$field] == $pid && $extra) {
            $v[$child] = list_to_tree2($cate, $field, $child, $v['id'], $callback);
            $arr[]     = $v;
        }
    }
    return $arr;
}



function getLatelyTime($type = '', $now = "")
{
    $now = $now ? strtotime($now) : time();
    $result = [];
    if ($type == 'hour') {
        //最近一天
        for ($i = 0; $i < 24; $i++) {
            $result[] = date('H', strtotime('-' . $i . ' hour', $now));
        }
    } elseif ($type == 'week') {
        //最近一周
        for ($i = 0; $i < 7; $i++) {
            $result[] = date('m-d', strtotime('-' . $i . ' day', $now));
        }
    } elseif ($type == 'month') {
        //最近一个月
        for ($i = 0; $i < 30; $i++) {
            $result[] = date('m-d', strtotime('-' . $i . ' day', $now));
        }
    } elseif ($type == 'year') {
        //最近一年
        for ($i = 0; $i < 12; $i++) {
            $result[] = date('Y-m', strtotime('-' . $i . ' month', $now));
        }
    }
    return $result;
}


// 上传图片
function uploadFile($key)
{
    // "jpeg", "jpg", "png", 'JPG', 'PNG', 'gif', 'webp'
    try {
        $M = 1024 * 2048 * 10;
        validate([
            $key => 'fileSize:' . $M . '|fileExt:jpg,png,gif,jpeg,JPG,PNG,webp'
        ])->check(request()->file());
        $file = request()->file($key);
        // 单图上传
        if (!is_array($file)) {
            $saveName = \think\facade\Filesystem::disk('images')->putFile('images', $file, 'uniqid');
            // return str_replace('\\', '/', $saveName);
            return str_replace('\\', '', '/uploads/' . $saveName);
        }
        // // 多图上传
        $result = [];
        foreach ($file as $v) {
            $saveName = \think\facade\Filesystem::disk('images')->putFile('images', $v, 'uniqid');
            // return str_replace('\\', '/', $saveName);
            $result[] = str_replace('\\', '', '/uploads/' . $saveName);
        }
        return $result;
    } catch (think\exception\ValidateException $e) {
        ApiException($e->getMessage());
    }
}


// 验证用户名是什么格式，昵称/邮箱/手机号
function filterLoginMethod($data)
{
    // 验证是否是手机号码
    if (preg_match('^1(3|4|5|7|8)[0-9]\d{8}$^', $data)) {
        return 'phone';
    }

    if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $data)) {
        // 验证是否是邮箱
        return 'email';
    }

    return 'username';
}
// 验证是否是手机号码
function checkPhone($phone)
{
    $check = '/^(1(([3456789][0-9])|(47)))\d{8}$/';
    if (preg_match($check, $phone)) {
        return true;
    } else {
        return false;
    }
}
// 验证是否是邮箱
function checkEmail($email)
{

    $regexp = "/^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
    if (preg_match($regexp, $email)) {
        return true;
    } else {
        return false;
    }
}
