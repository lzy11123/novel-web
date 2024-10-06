<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Category extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    // 关闭自动化实例模型
    // protected $autoModel = false;
    // protected $modelPath = 'admin/Manager';

    // 是否开启参数自动验证
    // protected $autoValidate = true;

    // 自定义验证场景
    // protected $autoValidateScenes = [
    //     'save' => 'save1'
    // ];

    // 不需要自动验证的相关信息
    protected $excludeValidateCheck = ['index'];


    public function index()
    {
        //
        // $param = $this->request->param();

        $list = $this->M->order([
            'order' => 'asc',
            'create_time' => 'asc',
        ])->select();

        $list = list_to_tree2($list->toArray(), 'category_id');


        return showSuccess($list);
    }



    public function sortCategory()
    {
        $data = $this->request->param('sortdata');
        $data = json_decode($data, true);
        // dump($data);
        $res = $this->M->saveAll($data);
        return showSuccess($res);
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
        $res = $this->M->save($request->param());

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
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $m = $this->request->Model;

        return showSuccess($m->delete());
    }
}
