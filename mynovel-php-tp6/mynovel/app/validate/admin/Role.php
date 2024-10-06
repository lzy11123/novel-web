<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class Role extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|integer|>:0|isExist:Role',
        'page' => 'require|integer|>:0',
        'status' => 'require|in:0,1',
        'name' => 'require'
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
        'save' => ['name', 'status'],
        'update' => ['id', 'name', 'status'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
        'setRules' => ['id'],
    ];
}
