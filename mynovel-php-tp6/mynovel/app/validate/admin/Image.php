<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;
use think\Validate;

class Image extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|integer|>:0|isExist:Image',
        'image_class_id|图片分类id' => 'integer|>=:0|isExist:ImageClass,false',
        'name' => 'require',
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
        'save' => ['image_class_id'],
        'update' => ['id', 'name'],
        'delete' => ['id'],
        'deleteAll' => ['ids'],
    ];
}
