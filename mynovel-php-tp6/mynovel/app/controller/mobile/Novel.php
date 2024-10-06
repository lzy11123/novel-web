<?php

declare(strict_types=1);

namespace app\controller\mobile;

use app\ApiBaseController;
use think\Request;

class Novel extends ApiBaseController
{

    protected $autoValidate = false;
    // 最新上架
    public function newlist()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));

        $list = \app\model\Novel::where('status', '1')->page($page, $limit)->order('create_time', 'desc')->select();

        return showSuccess([
            'list' => $list,
        ]);
    }

    // 热门推荐
    public function recommend()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));

        $list = \app\model\Novel::where('status', '1')->page($page, $limit)->order('order', 'desc')->select();

        return showSuccess([
            'list' => $list,
        ]);
    }
    // 最新排行
    public function readlist()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));

        $list = \app\model\Novel::where('status', '1')->page($page, $limit)->order('order', 'desc')->select();

        return showSuccess([
            'list' => $list,
        ]);
    }

    // 根据分类查小说
    public function getCategoryNovel()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));
        $keyword = getValByKey('keyword', $param, '');

        // dump($param);
        $where[] = ['status', '=', 1];
        if ($param['id'] != 0) {
            $where[] = ['category_id', '=', $param['id']];
        } else {
            return ApiException('该分类不存在');
        }

        // if ($keyword) {
        //     $where[] = [
        //         ['name', 'like', '%' . $keyword . '%']
        //     ];
        // }
        $list = \app\model\Novel::where($where)->page($page, $limit)->select();

        return showSuccess([
            'list' => $list,
        ]);
    }
    // 搜索
    public function search()
    {
        $param = request()->param();
        $page = intval(getValByKey('page', $param, 1));
        $limit = intval(getValByKey('limit', $param, 10));
        $keyword = getValByKey('keyword', $param, '');
        $where[] = ['status', '=', 1];
        if ($keyword) {
            $where[] = [
                'name|tag|author', 'like', '%' . $keyword . '%'
            ];
        }
        $list = \app\model\Novel::where($where)->page($page, $limit)->select();

        return showSuccess([
            'list' => $list,
        ]);
    }


    // 小说详情
    public function read()
    {
        $param = request()->param();
        $tag = 'user';
        $model = '\\app\\model\\User';

        // 获取token    
        $token = request()->header('token');

        $user = [];
        // token存在
        if ($token) {
            // 没有登录
            $user = cms_getUser([
                'token' => $token,
                'tag' => $tag
            ]);

            if (!$user) {
                ApiException('非法token,请先登录');
            }

            // token正确
            request()->UserModel = \app\model\User::find($user['id']);
            // 当前用户被禁用
            if (!request()->UserModel || !request()->UserModel->status) {
                ApiException('当前用户已被禁用');
            }
        }



        $list = \app\model\Novel::where('id', $param['id'])->find();
        $chapterList = \app\model\NovelDetail::where([['novel_id', '=', $param['id'], ['status', '=', 1]]])->field(['id', 'name'])->order([
            'order' => 'asc',
            'create_time' => 'asc'
        ])->select();


        $list['collect'] = false;
        if ($user) {

            
            $res = \app\model\Collection::where([[['novel_id', '=', $list['id']], ['user_id', '=', $user['id']]]])->find();



            $list['collect'] = $res ? true : false;
        }

        return showSuccess([
            'novel' => $list,
            'list' => $chapterList
        ]);
    }
}
