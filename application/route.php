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

use think\Route;

// Banner
Route::get('banner/:id','api/Banner/getBanner'); // 访问方式 banner/1

// product
Route::get('product/:id','api/Product/getByCategory'); // 访问方式 product/1
Route::get('product/paginate','api/product/getAllCategory');

