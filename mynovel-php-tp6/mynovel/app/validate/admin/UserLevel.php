<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;


class UserLevel extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:UserLevel',
        'name' => 'require',
        'level' => 'integer|>=:0',
        'status' => 'require|in:0,1',
        'discount' => 'integer|>=:0',
        'max_price' => 'integer|>=:0',
        'max_times' => 'integer|>=:0',
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
        'save' => ['name', 'level', 'status', 'discount', 'max_price', 'max_times'],
        'update' => ['id', 'name', 'level', 'status', 'discount', 'max_price', 'max_times'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
    ];
}
