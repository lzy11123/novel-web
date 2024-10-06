<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class Novel extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:Novel',
        'name' => 'require',
        'status' => 'require|in:0,1',
        'cover' => 'url',
        'category_id' => 'require|integer|>:0|isExist:Category,false',
        'desc' => 'require',
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
        'save' => ['name', 'desc', 'category_id', 'cover', 'status'],
        'update' => ['id', 'name', 'desc', 'category_id', 'cover', 'status'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
        'novels' => ['id', 'page'],
    ];
}
