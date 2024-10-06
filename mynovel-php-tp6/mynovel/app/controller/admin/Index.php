<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use app\IndexBaseController;
use think\facade\Db;
use think\Request;

class Index extends IndexBaseController
{
    protected $autoNewModel = false;
    protected $excludeValidateCheck = ["statistics1", "statistics2", "statistics3"];

    // 统计1
    public function statistics1()
    {
        // 总 管理员
        $totalManager= Db::table('manager')->count("id");
        // 总用户
        $totalUser = Db::table('user')->count("id");
        // 总小说
        $totalNovel = Db::table('novel')->count("id");
        // 总图片
        $totalImage = Db::table('image')->count("id");

        return showSuccess([
            "panels" => [
                [
                    "title" => "管理员",
                    "value" => $totalManager,
                    "unit" => "位",
                    "unitColor" => "success",
                    "subTitle" => "总人数",
                    "subValue" => $totalManager,
                    "subUnit" => ""
                ],
                [
                    "title" => "总用户",
                    "value" => $totalUser,
                    "unit" => "位",
                    "unitColor" => "danger",
                    "subTitle" => "总人数",
                    "subValue" => $totalUser,
                    "subUnit" => ""
                ],
                [
                    "title" => "小说",
                    "value" => $totalNovel,
                    "unit" => "本",
                    "unitColor" => "",
                    "subTitle" => "本",
                    "subValue" => $totalNovel,
                    "subUnit" => ""
                ],
                [
                    "title" => "图片",
                    "value" => $totalImage,
                    "unit" => "张",
                    "unitColor" => "warning",
                    "subTitle" => "图片数量",
                    "subValue" => $totalImage,
                    "subUnit" => "张"
                ]
            ]
        ]);
    }
	public function statistics2(){
	     $list = \app\model\Category::where(['category_id' => 0,'status' => 1])->with(['novels'])->order([
            'order' => 'asc',
            'create_time' => 'asc',
        ])->select();
        $data = [];
        foreach ($list as $k => $v) {
	        $data[$k]['name'] = $v['name'];
	        $data[$k]['value'] = count($v['novels']);
	    }
        
        return showSuccess($data);
	}
    // 统计3
	public function statistics3(){

	    $type = request()->param("type","week");
	    
	    $d = date('Y-m-d');
	    $m = date('Y-m');
	    $time = strtotime($d);
	    $endTime = date('Y-m-d', strtotime('+1 day', $time));
	    $table = Db::table('novel');
	    $res = [];
	    if($type == "week"){
	        $startTime = date('Y-m-d', strtotime('-6 day', $time));
	        $res = $table->field('FROM_UNIXTIME(create_time,"%m-%d") as time,COUNT(id) AS num')
	            ->whereBetweenTime('create_time', $startTime, $endTime)
	            ->group('time')->select()->toArray();
	    } elseif($type == "month"){
	        $startTime = date('Y-m-d', strtotime('-29 day', $time));
	        $res = $table->field('FROM_UNIXTIME(create_time,"%m-%d") as time,COUNT(id) AS num')
	            ->whereBetweenTime('create_time', $startTime, $endTime)
	            ->group('time')->select()->toArray();
	    } elseif($type == "hour"){
	        $startTime = date('Y-m-d', strtotime('-23 hour', $time));
	        $res = $table->field('FROM_UNIXTIME(create_time,"%H") as time,COUNT(id) AS num')
	            ->whereBetweenTime('create_time', $startTime, $endTime)
	            ->group('time')->select()->toArray();
	    } elseif($type == 'year') {
	          $startTime = date('Y-m', strtotime('-12 months', strtotime($m)));
	          $endTime = date('Y-m');
	          
	          $res = $table->field('FROM_UNIXTIME(create_time,"%Y-%m") as time,COUNT(id) AS num')
	            ->whereBetweenTime('create_time', $startTime, $endTime)
	            ->group('time')->select()->toArray();
	    }

	    $lables = getLatelyTime($type,$d);
        
	    $result = [
	        "x"=>[],
	        "y"=>[]
	    ];
	    foreach ($lables as $x) {
	        $arr = array_filter($res,function($v) use($x){
	            return $v["time"] == $x;
	        });
	        $result["x"][] = $x;
	        $result["y"][] = count($arr) > 0 ? (current($arr))["num"] : 0;
	    }
	    
	    return showSuccess($result);
	}
	

	
}
