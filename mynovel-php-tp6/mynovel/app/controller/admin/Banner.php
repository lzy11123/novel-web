<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Banner extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $param = request()->param();

        $limit = intval(getValByKey('limit', $param, 10));


        $totalCount = $this->M->count();

        $list = $this->M->page($param['page'], $limit)->order(['id' => 'desc'])->select();


        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
        ]);
    }
}
