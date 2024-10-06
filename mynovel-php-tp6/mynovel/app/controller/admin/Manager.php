<?php

declare(strict_types=1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;

class Manager extends BaseController
{

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
    protected $excludeValidateCheck = ['logout', 'getInfo', 'updatepassword'];

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
        $keyword = getValByKey('keyword', $param, '');
        $where = [
            ['username', 'like', '%' . $keyword . '%']
        ];

        $totalCount = $this->M->where($where)->count();
        $list = $this->M->page($param['page'], $limit)
            ->where($where)
            ->with(['role'])
            ->order(['id' => 'desc'])
            ->select()
            ->hidden(['password']);
        $role = \app\model\Role::field(['id', 'name'])->select();
        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
            'role' => $role
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
        // var_dump($request->param());


        // $M =  app('app\model\Manager');

        // $param = $request->param();

        $param = $request->only(['username', 'password', 'avatar', 'role_id', 'status']);

        // 参数验证
        // $V  = new \app\validate\admin\Manager();
        // $V  = app('app\validate\admin\Manager');

        // if (!$V->scene('save')->check($param)) {
        //     ApiException($V->getError());
        // }


        $param['super'] = 0;

        $res =  $this->M->save($param);



        // dump($res);

        return showSuccess($res);
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
        $param = $request->only(['id', 'username', 'password', 'avatar', 'role_id', 'status']);


        // $Model =  $this->M->find($param['id']);
        // halt($Model);
        $res = $request->Model->save($param);
        return showSuccess($res);
    }

    /**
     * 修改管理员状态
     */
    public function updateStatus()
    {


        // 不能禁用自己
        if ($this->request->UserModel->id == $this->request->Model->id) {
            ApiException('不允许禁用自己');
        }


        $res =  $this->request->Model->save([
            'status' => $this->request->param('status')
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

        $manager  = $this->request->Model;
        //不能删除自己
        if ($this->request->UserModel->id  == $manager->id) {
            ApiException('不能删除自己');
        }

        // 不能删除超级管理员账号
        if ($manager->super  == 1) {
            ApiException('不能删除超级管理员');
        }

        $res = $manager->delete();

        return showSuccess($res);
    }

    // 登录
    public function login()
    {

        // dump($this->request->UserModel);
        $user =  cms_login([
            'data' => $this->request->UserModel
        ]);


        // dump($user);

        // 获取当前用户所有权限
        $data = $this->M->where('id', $user['id'])->with([
            'role' => function ($query) {
                // dump($query);
                $query->with([
                    'rules' => function ($q) {
                        // dump($q);
                        // $q->where('status', 1)->order('create_time', 'desc');
                    }
                ]);
            }
        ])->find()->toArray();




        $data['token'] = $user['token'];
        $data['tree'] = [];


        unset($data['password']);



        // 规则名称，按钮级别显示
        $data['ruleNames'] = [];
        // 无限级分类
        $rules = $data['role']['rules'];
        // 超级管理员
        if ($data['super'] === 1) {
            $rules = \app\model\Rule::where('status', 1)->select()->toArray();
        }
        $data['menus'] = list_to_tree2($rules, 'rule_id', 'child', 0, function ($item) {
            return $item['menu'] === 1;
        });
        // 权限规则数组
        foreach ($data['role']['rules'] as $v) {
            if ($v['condition'] && $v['name']) {
                $data['ruleNames'][] = $v['name'];
            }
        }

        return showSuccess($data);
    }


    public function getInfo()
    {

        $user = $this->request->UserModel;

        // 获取当前用户所有权限
        $data = $this->M->where('id', $user['id'])->with([
            'role' => function ($query) {
                // dump($query);
                $query->with([
                    'rules' => function ($q) {
                        // dump($q);
                        // $q->where('status', 1)->order('create_time', 'desc');
                    }
                ]);
            }
        ])->find()->toArray();




        $data['token'] = $user['token'];
        $data['menus'] = [];

        unset($data['password']);

        // 规则名称，按钮级别显示
        $data['ruleNames'] = [];
        // 无限级分类
        $rules = $data['role']['rules'];
        // 超级管理员
        if ($data['super'] === 1) {
            $rules = \app\model\Rule::where('status', 1)->select()->toArray();
        }
        $data['menus'] = list_to_tree2($rules, 'rule_id', 'child', 0, function ($item) {
            return $item['menu'] === 1;
        });
        // 权限规则数组
        foreach ($data['role']['rules'] as $v) {
            if ($v['condition'] && $v['name']) {
                $data['ruleNames'][] = $v['condition'];
            }
        }
        return showSuccess($data);
    }

    /**
     * 
     * 退出登录
     * 
     */
    public function logout()
    {
        $res =  cms_logout([
            'token' => $this->request->header('token')
        ]);

        return showSuccess($res);
    }


    public function updatepassword(Request $request)
    {
        //     // 演示数据
        // 	if ($id == 50) {
        // 		$this->TestException();
        // 	}
        // 超级管理员和演示数据禁止操作
        // 	if ($request->Model->super) {
        // 		ApiException('超级管理员禁止修改');
        // 	}

        $param = $request->param();
        $user = $request->UserModel;

        if (array_key_exists('password', $param) && $param['password'] == '') {
            ApiException('新密码不能为空');
        }

        if (!password_verify($param["oldpassword"], $user->password)) {
            ApiException('旧密码不正确');
        }

        if ($param['password'] != $param['repassword']) {
            ApiException('新密码和确认密码不一致');
        }

        // 	$user->id == 50 || $user->super
        // if($user->super){
        //     ApiException('超级管理员禁止修改~');
        // }


        $user->password = $param['password'];
        $res = $user->save();

        if ($res) {
            // 让当前登录失效
            cms_logout([
                'token' => $request->header('token')
            ]);
        }

        return showSuccess($res);
    }
}
