<?php

namespace app\controller;

use app\IndexBaseController;
use think\facade\View;

class Index extends IndexBaseController
{
    public function index()
    {

        $list = \app\model\Category::order([
            'order' => 'asc',
            'create_time' => 'asc',
        ])->select();
    
        
        // ApiException('我是故意错的');

        // return showSuccess($list);
        // return showError('记录不存在');
        View::assign('name','123456');
        View::assign('list', $list);
        return View::fetch();
    }
    
        
    public function details()
    {

        // abort(400,'我是故意错的');


        // ApiException('我是故意错的');

        $list = [1, 2, 3, 4, 5, 6];

        // return showSuccess($list);
        // return showError('记录不存在');
        return View::fetch();
    }
    
    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
