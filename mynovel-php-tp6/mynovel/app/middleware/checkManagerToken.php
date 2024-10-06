<?php

declare(strict_types=1);

namespace app\middleware;

class checkManagerToken
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

        // halt('checkManagerToken调试');

        $tag = 'manager';
        $model = '\\app\\model\\Manager';

        // 获取token    
        $token = $request->header('token');


        // token不存在
        if (!$token) {
            ApiException('非法token,请先登录');
        }

        // 没有登录
        $user = cms_getUser([
            'token' => $token,
            'tag' => $tag
        ]);

        if (!$user) {
            ApiException('非法token,请先登录');
        }


        // dump($user);

        // token正确
        $request->UserModel = \app\model\Manager::find($user['id']);
        // 当前用户被禁用
        if (!$request->UserModel || !$request->UserModel->status) {
            ApiException('当前用户已被禁用');
        }

        // 当前用户数据
        $request->userInfo = $user;
        // 验证当前用户权限 -- 超级管理员无需验证
        // 验证当前用户权限(超级管理员无需验证)
        if (!$request->UserModel->super) {
            if (!$request->UserModel->role->status) {
                return ApiException('你所在角色组已被禁用');
            }
            // $url = strtolower($request->controller() . '/' . $request->action());
            $ruleName = $request->rule()->getName();
            // dump($user);
            $r = (new $model)->hasRule($request->UserModel, $ruleName, $request->method());
            // admin.manager/getinfo

            if (!$r) {
                return ApiException('你没有权限');
            }
        }

        // 挂载用户

        return $next($request);
    }
}
