<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class User extends BaseController
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

        $keyword = getValByKey('keyword', $param, '');
        $user_level_id = getValByKey('user_level_id', $param, 0);


        $where = [];


        if ($keyword) {
            $name = filterLoginMethod($keyword);
            $where[] = [
                [$name, 'like', '%' . $keyword . '%']
            ];
        }

        if ($user_level_id != 0) {
            $where[] = ['user_level_id', '=', $user_level_id];
        }


        $totalCount = $this->M->where($where)->count();
        $list = $this->M->page($page, $limit)->where($where)->order([
            'id' => 'desc'
        ])->with(['userLevel'])->select()->hidden(['password']);



        $userLevel = \app\model\UserLevel::field(['id', 'name'])->select();

        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
            'user_level' => $userLevel,
        ]);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $param = $request->param();
        $user = $this->M->checkUnique('username', '用户名');
        if (!array_key_exists('password', $param)) {
            return ApiException('密码不能为空');
        }
        $this->M->checkUnique('phone', '手机');
        $this->M->checkUnique('email', '邮箱');
        return showSuccess($this->M->save($request->param()));
    }

    /**
     * 更新资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {

        $param = $request->param();

        if ($param['username'] != $request->Model->username) {
            $user = $this->M->checkUnique('username', '用户名');
        }
        // dump($param);

        $res = $request->Model->save($param);
        return showSuccess($res);
    }
}
