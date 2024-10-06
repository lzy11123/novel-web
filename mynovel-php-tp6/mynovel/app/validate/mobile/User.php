<?php

declare(strict_types=1);

namespace app\validate\mobile;

use app\validate\BaseValidate;

class User extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id' => 'require|integer|>:0|isExist:User',
        'username|用户名' => 'require|min:5|max:20',
        'password' => 'require|min:5|max:20',
        'repassword' => 'require|min:5|max:20',
        'avatar' => 'url',
        'status' => 'require|integer|in:0,1',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        // 'username.require' => '用户名不能为空',
        // 'password.require' => '密码不能为空',
        // 'role_id.require' => '角色id不能为空',
        // 'status.require' => '账号状态不能为空',
    ];

    protected $scene = [];


    // 创建管理员
    public function sceneRegister()
    {
        return $this->only([
            'username', 'password',
            'newpassword'
        ])->append('username', 'unique:user');
    }

    // 创建管理员
    public function sceneUpdatepassword()
    {
        return $this->only([
            'oldpassword',
            'password',
            'repassword'
        ])->append('username', 'unique:user');
    }
    // // 修改管理员
    // public function sceneUpdate()
    // {
    //     $id = request()->param('id');
    //     // 'password',
    //     return $this->only(['id', 'username', 'avatar'])->append('username', 'unique:user,username,' . $id);
    // }

    // 登录场景
    public function sceneLogin()
    {
        return $this->only(['username', 'password'])->append('password', 'checklogin');
    }


    // 自定义验证规则
    // 验证账号密码
    public function checklogin($value, $rule = '', $data = '', $field = '')
    {


        // 验证账号
        $M = \app\model\User::where('username', $data['username'])->find();

        if (!$M) return '用户名不存在';
        // 验证密码
        if (!password_verify($data['password'], $M->password)) {
            return '密码错误';
        }
        // 将当前用户实例挂在到request
        request()->UserModel = $M;
        return true;
    }
}
