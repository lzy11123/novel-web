<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;

class UserLevel extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = $this->request->param();
        $limit = intval(getValByKey('limit', $param, 10));
        $page = getValByKey('page', $param, 1);
        $totalCount = $this->M->count();
        $list = $this->M->page($page, $limit)->order([
            'id' => 'desc'
        ])->select();
        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount
        ]);
    }
}
