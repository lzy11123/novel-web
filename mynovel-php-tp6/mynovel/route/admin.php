<?php

use think\facade\Route;


// 不需要验证
Route::group('admin', function () {
    // 管理员登录 
    Route::post('login', 'admin.Manager/login');
})->allowCrossDomain();

// 验证登录
Route::group('admin', function () {
    // 退出登录
    Route::post('logout', 'admin.Manager/logout')->name('logout');
    // 获取管理员登录信息
    Route::post('manager/getinfo', 'admin.Manager/getInfo');
    // 修改密码
    Route::post('updatepassword', 'admin.Manager/updatepassword');

    //
})->allowCrossDomain([
    "Access-Control-Allow-Headers" => "token"
])->middleware(\app\middleware\hasManagerLogin::class);



// 需要验证登录-验证权限  
Route::group('admin', function () {
    
        
    Route::get('statistics1', 'admin.Index/statistics1')->name('getStatistics1');
    Route::get('statistics2', 'admin.Index/statistics2')->name('getStatistics2');
    Route::get('statistics3', 'admin.Index/statistics3')->name('getStatistics3');
    
    // 站点设置
    // 获取站点信息
    Route::get('site', 'admin.Site/site')->name('getSite');
    // 修改站点信息
    Route::post('site/:id', 'admin.Site/update')->name('updateSite');
    // 创建站点信息
    Route::post('site', 'admin.Site/save')->name('createSite');
    /**
     * 
     * 管理员板块
     */


    //管理员列表
    Route::get('manager/:page', 'admin.Manager/index')->name('getManagerList');


    // 删除管理员
    Route::post('manager/:id/delete', 'admin.Manager/delete')->name('deleteManager');
    // 修改管理员状态
    Route::post('manager/:id/update_status', 'admin.Manager/updateStatus')->name('updateManagerStatus');
    // 更新管理员
    Route::post('manager/:id', 'admin.Manager/update')->name('updateManager');
    // 创建管理员
    Route::post('manager', 'admin.Manager/save')->name('createManager');

    /**
     * 角色板块
     */
    // 角色列表
    Route::get('role/:page', 'admin.Role/index')->name('getRoleList');

    // 配置角色权限
    Route::post('role/set_rules', 'admin.Role/setRules')->name('setRoleRules');
    // 删除角色
    Route::post('role/:id/delete', 'admin.Role/delete')->name('deleteRole');
    // 修改角色状态
    Route::post('role/:id/update_status', 'admin.Role/updateStatus')->name('updateRoleStatus');
    // 修改角色
    Route::post('role/:id', 'admin.Role/update')->name('updateRole');
    // 创建角色
    Route::post('role', 'admin.Role/save')->name('createRole');


    /**
     * 权限板块
     */
    //权限列表
    Route::get('rule/:page', 'admin.Rule/index')->name('getRuleList');

    // 删除权限
    Route::post('rule/:id/delete', 'admin.Rule/delete')->name('deleteRule');
    // 修改权限状态
    Route::post('rule/:id/update_status', 'admin.Rule/updateStatus')->name('updateRuleStatus');
    // 修改权限
    Route::post('rule/:id', 'admin.Rule/update')->name('updateRule');
    // 创建权限
    Route::post('rule', 'admin.Rule/save')->name('createRule');



    /**
     * 图片分类板块
     */
    // 图片列表
    Route::get('imageclass/:id/image/:page$', 'admin.ImageClass/images')->name('getCurrentImageList');

    //图片分类列表
    Route::get('imageclass/:page', 'admin.ImageClass/index')->name('getImageClassList');

    // 删除图片分类
    Route::post('imageclass/:id/delete', 'admin.ImageClass/delete')->name('deleteImageClass');
    // 修改图片分类
    Route::post('imageclass/:id', 'admin.ImageClass/update')->name('updateImageClass');
    // 创建图片分类
    Route::post('imageclass', 'admin.ImageClass/save')->name('createImageClass');

    /**
     * 图片板块
     */
    // 创建图片
    Route::post('image/upload', 'admin.Image/save')->name('uploadImage');
    // 批量删除图片
    Route::post('image/delete_all$', 'admin.Image/deleteAll')->name('deleteAllImage');
    // 删除图片
    Route::post('image/:id/delete', 'admin.Image/delete')->name('deleteImage');
    // 修改图片名称
    Route::post('image/:id', 'admin.Image/update')->name('updateImageName');

    /**
     * 
     * 分类板块
     * 
     */
    // 分类列表
    Route::get('category', 'admin.Category/index')->name('getCategoryList');
    // 分类排序
    Route::post('category/sort', 'admin.Category/sortCategory')->name('sortCategory');
    // 修改分类状态
    Route::post('category/:id/update_status', 'admin.Category/updateStatus')->name('updateCategoryStatus');
    // 删除分类
    Route::post('category/:id/delete', 'admin.Category/delete')->name('deleteCategory');
    // 修改分类
    Route::post('category/:id', 'admin.Category/update')->name('updateCategory');
    // 创建分类
    Route::post('category', 'admin.Category/save')->name('createCategory');

    /**
     * 小说
     */

    // 小说章节列表
    Route::get('novel/:id/noveldetail/:page$', 'admin.Novel/novels')->name('getCurrentNovelList');
    // 标签列表
    Route::get('novel/tag', 'admin.Novel/tag')->name('getNovelTagList');
    //小说列表
    Route::get('novel/:page', 'admin.Novel/index')->name('getNovelList');


    // 修改小说状态
    Route::post('novel/:id/update_status', 'admin.Novel/updateStatus')->name('updateNovelStatus');
    // 删除小说
    Route::post('novel/:id/delete', 'admin.Novel/delete')->name('deleteNovel');
    // 修改小说
    Route::post('novel/:id', 'admin.Novel/update')->name('updateNovel');
    // 创建小说
    Route::post('novel', 'admin.Novel/save')->name('createNovel');



    /**
     * 小说章节板块
     */
    // 修改小说章节状态
    Route::post('noveldetail/:id/update_status', 'admin.NovelDetail/updateStatus')->name('updateNovelDetailsStatus');
    // 删除小说章节
    Route::post('noveldetail/:id/delete', 'admin.NovelDetail/delete')->name('deleteNovelDetail');
    // 修改小说章节
    Route::post('noveldetail/:id', 'admin.NovelDetail/update')->name('updateNovelDetail');
    // 创建小说章节
    Route::post('noveldetail', 'admin.NovelDetail/save')->name('createNovelDetail');



    /**
     * 
     * banner模块
     */
    // banner列表
    Route::get('banner/:page', 'admin.Banner/index')->name('getBannerList');
    // 修改banner状态
    Route::post('banner/:id/update_status', 'admin.Banner/updateStatus')->name('updateBannerStatus');

    // 删除banner
    Route::post('banner/:id/delete', 'admin.Banner/delete')->name('deleteBanner');

    // 修改banner
    Route::post('banner/:id', 'admin.Banner/update')->name('updateBanner');
    // 创建banner
    Route::post('banner', 'admin.Banner/save')->name('createBanner');


    /**
     * 
     * 会员等级模块
     */
    // 会员等级列表
    Route::get('user_level/:page', 'admin.UserLevel/index')->name('getUserLevelList');
    // 修改会员等级状态
    Route::post('user_level/:id/update_status', 'admin.UserLevel/updateStatus')->name('updateUserLevelStatus');
    // 删除会员等级
    Route::post('user_level/:id/delete', 'admin.UserLevel/delete')->name('deleteUserLevel');
    // 修改会员等级
    Route::post('user_level/:id', 'admin.UserLevel/update')->name('updateUserLevel');
    // 创建会员等级
    Route::post('user_level', 'admin.UserLevel/save')->name('createUserLevel');


    /**
     * 
     * 用户模块
     */
    // 用户列表
    Route::get('user/:page', 'admin.User/index')->name('getUserList');
    // 修改用户状态
    Route::post('user/:id/update_status', 'admin.User/updateStatus')->name('updateUserStatus');
    // 删除用户
    Route::post('user/:id/delete', 'admin.User/delete')->name('deleteUser');
    // 修改用户
    Route::post('user/:id', 'admin.User/update')->name('updateUser');
    // 创建用户
    Route::post('user', 'admin.User/save')->name('createUser');



    //
})->middleware(\app\middleware\checkManagerToken::class);
