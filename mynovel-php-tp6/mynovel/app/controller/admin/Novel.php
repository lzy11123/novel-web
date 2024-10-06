<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Novel extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */


    // 不需要自动验证的相关信息
    protected $excludeValidateCheck = ['tag'];


    public function index()
    {
        //
        $param = $this->request->param();
        $limit = intval(getValByKey('limit', $param, 10));
        $page = getValByKey('page', $param, 1);

        $keyword = getValByKey('keyword', $param, '');
        $category_id = getValByKey('category_id', $param, 0);


        $where = [];


        if ($keyword) {
            $where[] = [
                ['name', 'like', '%' . $keyword . '%']
            ];
        }

        if ($category_id != 0) {
            $where[] = ['category_id', '=', $category_id];
        }


        $totalCount = $this->M->where($where)->count();
        $list = $this->M->page($page, $limit)->where($where)->with(['category'])->order([
            'order' => 'desc',
            'create_time' => 'desc',
            'id' => 'desc'
        ])->select();



        $category = \app\model\Category::where([['status', '=', 1], ['category_id', '<=', 0]])->field(['id', 'name'])->order('order', 'asc')->select();

        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
            'category' => $category,
        ]);
    }


    // 获取当前小说的章节
    public function novels()
    {
        //
        $param = request()->param();
        $order = getValByKey('order', $param, 'desc');
        $limit = getValByKey('limit', $param, 10);
        $page = getValByKey('page', $param, 1);
        $keyword = getValByKey('keyword', $param, false);

        $model = $this->request->Model->novelDetail();

        $where = [];

        if ($keyword) {
            $where[] = [
                ['name', 'like', '%' . $keyword . '%'],
            ];
        }

        $totalCount = $model->count();

        $list = $model->page($page, $limit)->where($where)->order([
            'order' => $order,
            'create_time' => $order,
            'id' => $order,
        ])->select();

        return showSuccess([
            'totalCount' => $totalCount,
            'list' => $list,
        ]);
    }


    // 获取标签列表
    public function tag()
    {
        // ->select()
        $category = \app\model\Category::where([['category_id', '>', 0], ['status', '=', 1]])->field(['id', 'name'])->select();

        return showSuccess([
            'list' => $category,
        ]);
    }
}
