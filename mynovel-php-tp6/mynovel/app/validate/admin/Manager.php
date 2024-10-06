<?php

declare(strict_types=1);

namespace app\validate\admin;

use think\Validate;

use app\validate\BaseValidate;

class Manager extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'id|管理员id' => 'require|integer|>:0|isExist:Manager',
        'username|管理员用户名' => 'require|min:5|max:20',
        'password' => 'require|min:5|max:20',
        'avatar' => 'url',
        'role_id' => 'require|integer|>:0',
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

    protected $scene = [
        // 'save' => ['username', 'password', 'role_id', 'avatar', 'status'],
        // 'update' => ['id', 'username', 'password', 'role_id', 'avatar', 'status'],
        'index' => ['page'],
        'delete' => ['id'],
        // 'login' => [],
        'updateStatus' => ['id', 'status'],
    ];


    // 创建管理员
    public function sceneSave()
    {
        return $this->only(['username', 'password', 'role_id', 'avatar', 'status'])->append('username', 'unique:manager');
    }


    // 修改管理员
    public function sceneUpdate()
    {
        $id = request()->param('id');
        // 'password',
        return $this->only(['id', 'username',  'role_id', 'avatar', 'status'])->append('username', 'unique:manager,username,' . $id);
    }

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
        $M = \app\model\Manager::where('username', $data['username'])->find();

        // dump($M);

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
