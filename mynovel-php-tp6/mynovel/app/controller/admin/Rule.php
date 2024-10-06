<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Rule extends BaseController
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

        $limit = intval(getValByKey('limit', $param, 1000));

        $page = getValByKey('page', $param, 1);

        $totalCount = $this->M->count();

        $list = $this->M->page($page, $limit)->order(['order' => 'desc','id' => 'desc'])->select()->toArray();


        return showSuccess([
            'list' => list_to_tree2($list, 'rule_id'),
            'rules' => list_to_tree($list, 'rule_id'),
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
     * 修改权限状态 
     * 
     */
    public function updateStatus()
    {

        $rule = $this->request->Model;

        $rule->status = $this->request->param('status');

        $res = $rule->save();

        return showSuccess($res);
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
        $res = $this->request->Model->delete();
        return showSuccess($res);
    }
}
