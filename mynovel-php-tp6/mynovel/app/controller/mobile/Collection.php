<?php

declare(strict_types=1);

namespace app\controller\mobile;

use think\Request;

class Collection
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    // 不做验证
    protected $autoValidate = false;

    public function index()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));

        $user = request()->UserModel;

        $list = \app\model\Collection::where('user_id', $user['id'])->with(['novel'])->page($page, $limit)->order('create_time', 'desc')->select();

        return showSuccess([
            'list' => $list,
        ]);
    }
}
