<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UserLevel extends Model
{
    //// 获取当前等级下的会员
    public function users()
    {
        return $this->hasMany('User');
    }

    public static function onBeforeDelete($userLevel)
    {
        // 获取当前等级下的所有会员
        $users = $userLevel->users;
        $users->each(function ($user) {
            $user->user_level_id = 0;
            $user->save();
        });
    }
}
