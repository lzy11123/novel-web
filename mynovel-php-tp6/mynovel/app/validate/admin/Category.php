<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class Category extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|integer|>:0|isExist:Category',
        'name' => 'require',
        'status' => 'require|in:0,1',
        'category_id' => 'require|integer|>=:0|isExist:Category,false',
        'order' => 'integer',
        'sortdata' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];


    protected $scene = [
        'save' => ['name', 'status', 'category_id', 'order'],
        'update' => ['id', 'name', 'status', 'category_id', 'order'],
        'updateStatus' => ['id', 'status',],
        'sortCategory' => ['sortdata'],
        'delete' => ['id']
    ];
}
