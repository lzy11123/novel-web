<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Skus extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $param = $this->request->param();
        $limit = intval(getValByKey('limit', $param, 10));
        $page = getValByKey('page', $param, 1);

        $totalCount = $this->M->count();

        $list = $this->M->page($page, $limit)->order([
            'order' => 'desc',
            'id' => 'desc',
        ])->select();

        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
        ]);
    }

    // 批量删除
    public function deleteAll()
    {
        $param = $this->request->param('ids');
        return showSuccess($this->M->where('id', 'in', $param)->delete());
    }
}
