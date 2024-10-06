<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class ImageClass extends Model
{
    // 获取当前相册下的图片
    public function images()
    {
        return $this->hasMany('Image');
    }
}
