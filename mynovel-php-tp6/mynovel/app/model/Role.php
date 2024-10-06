<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Role extends Model
{
    //

    //角色管理员  一对多
    public function managers()
    {
        return $this->hasMany('Manager');
    }


    // 多对多
    public function rules()
    {
        return $this->belongsToMany('Rule', 'role_rule');
    }
}
