<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Category extends Model
{
    //
    public function categories()
    {
        return $this->hasMany('Category');
    }
    public function novels()
    {
        return $this->hasMany('Novel');
    }
   
    public static function onAfterDelete($category)
    {

        // 删除对应的子分类
        $category->categories->each(function ($v) {
            $v->delete();
        });
    }
}
