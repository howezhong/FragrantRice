<?php
namespace app\api\controller;

use app\api\controller\Base;
use app\api\model\Category as CategoryModel;
use app\LIB\exception\MissException;

class Category extends Base
{
	/**
	 * 获取所有的类目列表
	 * @return json
	 */
    public function getAllCategories() {
        $result = CategoryModel::all([],'img'); // img关联模型里的img方法获取Image里的图片属性
        if ($result->isEmpty()) {
        	throw new MissException([
               'msg' => '还没有任何类目',
               'errorCode' => 50000
           ]);
        }
        return json($result);
    }
}
