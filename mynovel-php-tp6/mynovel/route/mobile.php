<?php

use think\facade\Route;


// 不需要验证
Route::group('api', function () {
    // 登录 
    Route::post('login', 'mobile.User/login');
    // 注册
    Route::post('register', 'mobile.User/register');

    // 分类列表
    Route::get('category', 'mobile.Category/index');

    // 首页数据
    Route::get('home', 'mobile.Index/index');

    // 小说板块
    // 最新上架
    Route::get('newlist/:page', 'mobile.Novel/newlist');
    // 热门推荐
    Route::get('recommend/:page', 'mobile.Novel/recommend');
    // 最新排行
    Route::get('readlist/:page', 'mobile.Novel/readlist');


    // 根据分类查小说
    Route::get('cate/:id/novel/:page$', 'mobile.Novel/getCategoryNovel');
    // 小说详情信息
    Route::get('read/novel/:id', 'mobile.Novel/read');
    // 搜索小说
    Route::get('search/:page', 'mobile.Novel/search');

    // 小说章节详情
    Route::get('chapter/:id', 'mobile.NovelDetail/read');
    // 获取小说章节列表
    Route::get('novel/:id/chapter', 'mobile.NovelDetail/chapter');
})->allowCrossDomain();

// 验证登录
Route::group('api', function () {
    // 退出登录
    Route::post('logout', 'mobile.User/logout');
    // 获取用户登录信息
    Route::post('user/getinfo', 'mobile.User/getInfo');
    // 修改密码
    Route::post('updatepassword', 'mobile.User/updatepassword');

    // 修改用户信息
    Route::post('user/updateuserinfo', 'mobile.User/updateuserinfo');

    // // 修改密码
    // Route::post('updatepassword', 'mobile.User/updatepassword');
    // 图片上传返回链接
    Route::post('upload', 'mobile.User/upload');

    //收藏
    Route::post('user/collect', 'mobile.User/collect');
    // 取消收藏
    Route::post('user/uncollect', 'mobile.User/uncollect');

    // 我的收藏列表'
    Route::get('collect/:page', 'mobile.Collection/index');
})->allowCrossDomain([
    "Access-Control-Allow-Headers" => "token"
])->middleware(\app\middleware\hasUserLogin::class);



// 需要验证登录-验证权限  
Route::group('api', function () {
})->middleware(\app\middleware\checkUserToken::class);;
