<?php

declare(strict_types=1);

namespace app\controller\mobile;

use app\ApiBaseController;
use think\Request;


class User extends ApiBaseController
{

    protected $excludeValidateCheck = ['updateuserinfo', 'logout', 'upload', 'collect', 'uncollect'];

    // 注册
    public function register()
    {
        $param = $this->request->param();

        // dump($param);
        if (array_key_exists('password', $param) && $param['password'] == '') {
            ApiException('密码不能为空');
        }

        if (array_key_exists('repassword', $param) && $param['repassword'] == '') {
            ApiException('确认密码不能为空');
        }

        if ($param['password'] != $param['repassword']) {
            ApiException('密码和确认密码不一致');
        }

        $m = app('\app\model\user');
        $param['status'] = 1;
        $res =  $m->save($param);

        return showSuccess($res);
    }

    // 登录
    public function login()
    {

        $tag = 'user';

        $user =  cms_login([
            'data' => $this->request->UserModel,
            'tag' => $tag,
        ]);

        // 获取当前用户所有权限
        $data = $this->M->where('id', $user['id'])->with(['userLevel'])->find()->toArray();


        $data['token'] = $user['token'];
        unset($data['password']);

        return showSuccess($data);
    }

    public function getInfo()
    {

        $user = $this->request->UserModel;

        // 获取当前用户所有信息
        $data = $this->M->where('id', $user['id'])->find()->toArray();

        $data['token'] = $user['token'];


        unset($data['password']);

        return showSuccess($data);
    }

    /**
     * 
     * 退出登录
     * 
     */
    public function logout()
    {
        $tag = 'user';
        $res =  cms_logout([
            'token' => $this->request->header('token'),
            'tag' => $tag
        ]);

        // dump($res);
        return showSuccess($res);
    }


    public function updatepassword(Request $request)
    {

        $param = $request->param();
        $user = $request->UserModel;
        $tag = 'user';
        if (array_key_exists('password', $param) && $param['password'] == '') {
            ApiException('新密码不能为空');
        }

        if (!password_verify($param["oldpassword"], $user->password)) {
            ApiException('旧密码不正确');
        }

        if ($param['password'] != $param['repassword']) {
            ApiException('新密码和确认密码不一致');
        }


        $user->password = $param['password'];
        $res = $user->save();

        if ($res) {
            // 让当前登录失效
            cms_logout([
                'token' => $request->header('token'),
                'tag' => $tag
            ]);
        }

        return showSuccess($res);
    }


    public function updateuserinfo()
    {
        $param = $this->request->param();
        $user = $this->request->UserModel;

        $res  = \app\model\User::where('nickname', $param['nickname'])->find();

        if ($res && $user['id'] != $res['id']) {
            return  ApiException('该昵称已存在');
        }


        if ($param['phone']) {

            $phone = checkPhone($param['phone']);
            if (!$phone) {
                return  ApiException('手机号码格式错误');
            }
        }

        if ($param['email']) {
            $email = checkEmail($param['email']);
            if (!$email) {
                return  ApiException('邮箱格式错误');
            }
        }


        $data = $user->save($param);

        return showSuccess($data);
    }

    // 收藏
    public function collect()
    {
        $param = $this->request->param();
        $user = $this->request->UserModel;

        $novel_id = $param['novel_id'];

        $m = app('app\model\Collection');

        $collect =  $m->where([['novel_id', '=', $novel_id], [
            'user_id', '=', $user['id']
        ]])->find();

        if ($collect) {
            return ApiException('您已经收藏过了');
        }

        $data = [
            'user_id' => $user['id'],
            'novel_id' => $novel_id,
        ];

        $res = $m->save($data);
        return showSuccess($res);
    }

    public function uncollect()
    {
        $param = $this->request->param();
        $user = $this->request->UserModel;

        $m = app('app\model\Collection');

        $collect =  $m->where([['id', '=', $param['id']], [
            'user_id', '=', $user['id']
        ]])->find();

        if (!$collect) {
            return ApiException('没有该收藏记录');
        }



        $res = $collect->delete();
        return showSuccess($res);
    }



    public function upload(Request $request)
    {
        //
        // 获取数据

        $param = request()->param();
        $file = request()->file('file');

        $user = $this->request->UserModel;
        // dump($file->getPathname());
        if ($file == null) {
            ApiException('未上传文件');
        }

        $result =  uploadFile('file');
        $http = input('server.REQUEST_SCHEME') . '://' . input('server.SERVER_NAME');
        // 写入数据库
        // 单图
        if (!is_array($result)) {

            $data = [
                'url' => $http . $result,
                'path' => $result,
            ];


            return showSuccess($data);
        }

        // 多图上传

        // dump($result);

        $data = [];

        foreach ($result as $v) {
            $data[] = [
                'url' => $http . $v,
                'path' => $v,
            ];
        }

        return showSuccess($data);
    }
}
