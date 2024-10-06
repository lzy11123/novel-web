<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class Rule extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:Rule',
        'rule_id' => 'require|integer|isExist:Rule,false',
        'status' => 'require|in:0,1',
        'name' => 'require',
        'menu' => 'require|in:0,1',
        'order' => 'require|integer',
        'method' => 'require|in:GET,POST,PUT,DELETE',
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
        'save' => ['rule_id', 'status', 'name', 'menu', 'order', 'method'],
        'update' => ['id', 'rule_id', 'status', 'name', 'menu', 'order', 'method'],
        'updateStatus' => ['id',  'status'],
        'delete' => ['id'],
    ];
}
