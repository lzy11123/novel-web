<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Manager extends Model
{
    // 修改器
    // 密码加密
    public function setPasswordAttr($value, $data)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public function hasRule($user, $rule, $method = false)
    {
        // 当前规则属于哪些用户组
        $where = ['status' => 1];
        $key = is_string($rule) ? 'condition' : 'id';
        $where[$key] = $rule;


        

        // 请求类型
        if ($method) {
            $where['method'] = $method;
        }


        $r = \app\model\Rule::where($where)->find();
        // 规则不存在
        if (!$r) {
            return false;
        }
        // 获取规则对应角色
        $roles = $r->roles;
        // 对比当前用户的角色
        $res = $roles->filter(function ($v) use ($user) {
            return $v->id === $user->role->id;
        });
        return !!$res->count();
    }
    //  一对多
    public function role()
    {
        return $this->belongsTo('Role');
    }
}
