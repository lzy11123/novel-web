<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class NovelDetail extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:NovelDetail',
        'name' => 'require',
        'content' => 'require',
        'status' => 'require|in:0,1',
        'novel_id|小说id' => 'require|integer|>=:0|isExist:Novel,false',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];


    protected $scene = [
        'save' => ['name', 'novel_id', 'content', 'status'],
        'update' => ['id', 'name', 'novel_id', 'status', 'content'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
    ];
}
