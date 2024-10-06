<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Site extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */


    protected $excludeValidateCheck = ['site'];

    public function index()
    {
        //
    }

    public function site()
    {
        //
        $res = $this->M->where('id', 1)->find();
        return showSuccess($res);
    }
}
