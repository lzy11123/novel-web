<?php

declare(strict_types=1);

namespace app\controller\mobile;

use app\ApiBaseController;
use think\Request;

class Index extends ApiBaseController
{

    // 是否自动实例化当前模型
    protected $autoModel = false;
    // 是否开启参数自动验证
    protected $autoValidate = false;
    // 首页数据
    public function index()
    {
        // 轮播图数据
        $bannerList = \app\model\Banner::where('status', 1)->order(['create_time' => 'desc', 'id' => 'desc'])->select();

        // 猜你喜欢
        $toLike = \app\model\Novel::where('status', 1)->order(rand())->limit(3)->select();


        // 首页小说展示数据

        $category = \app\model\Category::where([['status', '=', 1], ['category_id', '=', 0]])->order('order', 'asc')->limit(3)->select();
        $list = [];
        foreach ($category as $k => $v) {
            $v['books'] = \app\model\Novel::where([['status', '=', 1], ['category_id', '=', $v['id']]])->order('order', 'desc')->limit(3)->select();
            $list[$k] = $v;
        }

        return showSuccess([
            'banner' => $bannerList,
            'tolike' => $toLike,
            'list' => $list,
        ]);
    }
}
