<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;

class User extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:User',
        'username|用户名' => 'require|min:5|max:20',
        'password' => 'min:5|max:20',
        'status' => 'require|in:0,1',
        'avatar' => 'url',
        'nickname' => 'chsDash',
        'phone' => 'mobile',
        'email' => 'email',
        'user_level_id|会员等级' => 'require|integer|>=:0|isExist:User,false',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];


    protected $scene = [
        'index' => ['page'],
        'save' => ['username', 'password', 'nickname', 'phone', 'email', 'user_level_id', 'avatar', 'status'],
        'update' => ['id', 'username', 'password', 'nickname', 'phone', 'email', 'user_level_id', 'avatar', 'status'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
    ];
}
