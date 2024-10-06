<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    // 多对一
    public function userLevel()
    {
        return $this->belongsTo('UserLevel');
    }


    // 密码自动加密
    public function setPasswordAttr($value, $data)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // 验证唯一性
    public function checkUnique($key, $msg)
    {
        $data = request()->param($key);
        // 唯一性验证
        $user = $this->where($key, $data)->find();
        if ($user) {
            ApiException($msg . '已经存在');
        }
        return $user;
    }
}
