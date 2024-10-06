<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;

class Image extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
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
        // 获取数据

        $param = request()->param();
        $file = request()->file('img');
        $classId = getValByKey('image_class_id', $param, 0);


        if ($file == null) {
            ApiException('未上传图片');
        }

        $result =  uploadFile('img');
        $http = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');
        // dump($result);
        // 写入数据库
        // 单图
        if (!is_array($result)) {

            $data = [
                'url' => $http . $result,
                'name' => $result,
                'path' => $result,
                'image_class_id' => $classId,
            ];

            $res = $this->M->create($data);
            return showSuccess($res);
        }

        // 多图上传

        // dump($result);

        $data = [];

        foreach ($result as $v) {
            $data[] = [
                'url' => $http . $v,
                'name' => $v,
                'path' => $v,
                'image_class_id' => $classId,
            ];
        }
        $res = $this->M->saveAll($data);

        return showSuccess($res);
    }


    /**
     * 保存更新的资源
     * 修改（修改昵称）
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $res = request()->Model->save([
            'name' => request()->param('name')
        ]);

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
        $image = request()->Model;

        $http = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');

        $url = $_SERVER['DOCUMENT_ROOT'] . str_replace($http, '', $image->path);

        // dump($_SERVER['DOCUMENT_ROOT'] . $url);

        // dump(file_exists($url));


        $flag = file_exists($url);

        if ($flag) {
            unlink($url);
        }

        // // // 删除数据
        return showSuccess($image->delete());
    }

    public function deleteAll()
    {
        // 
        $param = request()->param('ids');
        // 找到所有数据
        $data = $this->M->where('id', 'in', $param)->select();


        $http = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');


        foreach ($data as $k => $v) {
            $url = $_SERVER['DOCUMENT_ROOT'] . str_replace($http, '', $v->path);
            // dump($url);

            if (file_exists($url)) {
                unlink($url);
            }
            // 删除当前数据
            $v->delete();
        }


        return showSuccess(true);
    }
}
