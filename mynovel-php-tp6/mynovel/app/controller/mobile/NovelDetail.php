<?php

declare(strict_types=1);

namespace app\controller\mobile;

use app\ApiBaseController;
use think\Request;

class NovelDetail extends ApiBaseController
{
    protected $autoValidate = false;
    // 获取章节详情
    public function read()
    {
        $param = request()->param();

        $list = \app\model\NovelDetail::where('id', $param['id'])->find();
        // $chapterList = \app\model\NovelDetail::where([['novel_id', '=', $list['novel_id']], ['status', '=', 1]])->field('id', 'name')->order([
        //     'order' => 'desc',
        //     'create_time' => 'desc'
        // ])->select();
        return showSuccess([
            'chapter' => $list,
        ]);
    }

    // 小说章节
    public function chapter()
    {
        $param = request()->param();

        $list = \app\model\NovelDetail::where('id', $param['id'])->find();
        $novel = \app\model\Novel::where('id', $list['novel_id'])->find();
        $chapterList = \app\model\NovelDetail::where([['novel_id', '=', $list['novel_id']], ['status', '=', 1]])->field(['id', 'name'])->order([
            'order' => 'asc',
            'create_time' => 'asc'
        ])->select();
        return showSuccess([
            'list' => $chapterList,
            'novel' => $novel ,
        ]);
    }
}
