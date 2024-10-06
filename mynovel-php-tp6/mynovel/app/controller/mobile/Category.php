<?php

declare(strict_types=1);

namespace app\controller\mobile;

use app\ApiBaseController;
use think\Request;

class Category extends ApiBaseController
{

    protected $excludeValidateCheck = ['index'];
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        //
        // $param = $this->request->param();

        $list = $this->M->where('status', 1)->order([
            'order' => 'asc',
            'create_time' => 'asc',
        ])->select();

        $list = list_to_tree2($list->toArray(), 'category_id');
            

        return showSuccess($list);
    }
}
