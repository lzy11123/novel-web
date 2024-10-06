<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Rule extends Model
{
    //角色-规则-多对多关系
    public function roles()
    {
        return $this->belongsToMany('Role', 'role_rule');
    }




    // 删除角色和规则的关联关系  ->文档多对多关系里面的
    public function delRoles($roleId)
    {
        return $this->roles()->detach($roleId);
    }
    // 删除对应的子分类
    public function childRules()
    {
        return $this->hasMany('Rule');
    }

    /**
     * 删除规则之前的操作
     * 1.删除角色和规则的关联关系
     * 2.删除对应的子分类
     */
    public static function onBeforeDelete($rule)
    {
        // 删除角色和规则的关联关系
        $roleIds = $rule->roles->map(function ($v) {
            return  $v->id;
        })->toArray();

        if (count($roleIds)) {
            $rule->delRoles($roleIds);
        }

        // 删除对应的子分类
        $rule->childRules->each(function ($v) {
            $v->delete();
        });
    }
}
