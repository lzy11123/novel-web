<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class ImageClass extends BaseController
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

    // 获取当前分类下的图片
    public function images()
    {
        //
        $param = request()->param();
        $order = getValByKey('order', $param, 'desc');
        $limit = getValByKey('limit', $param, 10);
        $page = getValByKey('page', $param, 1);
        $keyword = getValByKey('keyword', $param, false);

        $model = $this->request->Model->images();


        $where = [];

        if ($keyword) {
            $where[] = [
                ['name', 'like', '%' . $keyword . '%'],
            ];
        }


        $totalCount = $model->count();

        $list = $model->page($page, $limit)->where($where)->order([
            'id' => $order,
        ])->select();

        return showSuccess([
            'totalCount' => $totalCount,
            'list' => $list,
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
        $param = $this->request->param();
        $res = $this->request->Model->save($param);
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
