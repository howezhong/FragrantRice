<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 动态路由(TP5推崇方式)
use think\Route;

// Banner
Route::get('api/banner/:id','api/Banner/getBanner'); // 访问方式 api/banner/1

// Theme
Route::group('api/theme', function(){
    Route::get(':id','api/Theme/getComplexOne'); // api/theme/1
    Route::get('','api/Theme/getSimpleList');  // api/theme?ids=1,2,3
});

// product
Route::group('api/product', function(){
    Route::get('','api/Product/getByCategory'); // 根据类目ID获取该类目下所有商品(分页)
    Route::get('all/:id','api/Product/getAllInCategory');// 获取某分类下全部商品(不分页)
    Route::get('recent','api/Product/getRecent');
    Route::get('one/:id','api/Product/getOne');

});

Route::post('api/token/user','api/Token/getToken');

Route::post('api/address','api/Address/createOrUpdateAddress');