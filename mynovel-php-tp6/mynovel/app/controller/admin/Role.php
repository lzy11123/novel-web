<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Role extends BaseController
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

        $list = $this->M->page($param['page'], $limit)->with(['rules' => function ($q) {
            // $q->alias('a')->field(['a.id']);
        }])->order(['id' => 'desc'])->select();


        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //



    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $param = $request->param();
        $res = $this->M->save($param);


        return showSuccess($res);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $param = $request->param();

        $res = $request->Model->save($param);

        return showSuccess($res);
    }


    /**
     * 
     * 修改角色状态
     * 
     */
    public function updateStatus()
    {
        $role = $this->request->Model;
        $role->status = $this->request->param('status');

        return showSuccess($role->save());
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {

        //
        $role = $this->request->Model;

        // 判断该角色下是否配置了管理员，如果配置需要先移除配置
        if ($role->managers->count()) {
            ApiException('请先修改该角色对应的管理员');
        }
        $res = $role->delete();

        return showSuccess($res);
    }

    /**
     * 给角色配置权限
     */

    public function setRules()
    {

        // [1,2,3]  =>   [] ->   添加 123;
        // [1,2,3] => [1,2] -> 添加3；
        // [1,2,3] => [1,3,4] -> 添加2  删除4
        $param = request()->param();

        $role = $this->request->Model;

        $ruleIds = getValByKey('rule_ids', $param, []);


        // 获取当前角色的所有权限id
        $ids = \app\model\RoleRule::where('role_id', $role->id)->column('rule_id');

        // dump($ids);

        // 增加权限
        $addIds = array_diff($ruleIds, $ids);
        // 删除权限
        $delIds = array_diff($ids, $ruleIds);

        // dump($addIds);
        // dump($delIds);

        $RoleRule = new \app\model\RoleRule();


        // 新增权限
        if (count($addIds)) {
            $addData = [];

            foreach ($addIds as $v) {
                $addData[] = [
                    'role_id' => $role->id,
                    'rule_id' => $v,
                ];
            }

            $RoleRule->saveAll($addData);
        }

        // 删除权限  
        if (count($delIds)) {
            $RoleRule->where([
                ['role_id', '=', $role->id],
                ['rule_id', 'in', $delIds],
            ])->delete();
        }

        return showSuccess(true);
    }
}
