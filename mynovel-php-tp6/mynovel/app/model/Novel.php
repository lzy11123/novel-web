<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Novel extends Model
{
    //
    public function novelDetail()
    {
        return $this->hasMany('NovelDetail');
    }

    // 多对一
    public function category()
    {
        return $this->belongsTo('Category');
    }
}
