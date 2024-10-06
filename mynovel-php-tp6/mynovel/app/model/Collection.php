<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Collection extends Model
{
    //
    // 多对一
    public function novel()
    {
        return $this->belongsTo('Novel');
    }
}
