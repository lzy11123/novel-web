<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;

class Skus extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:Skus',
        'status' => 'require|in:0,1',
        'name' => 'require',
        'order' => 'require|integer',
        'default' => 'require',
        'ids' => 'require|array'
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
        'save' => ['name', 'status', 'order', 'default'],
        'update' => ['id', 'name', 'status', 'order', 'default'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
        'deleteAll' => ['ids'],
    ];
}
