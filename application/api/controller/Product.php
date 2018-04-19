<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;

class Product extends Base
{
    public function getByCategory($id)
    {
    	// 校验ID
        (new IDMustBePositiveInt())->goCheck();

        // 获取商品详情
        $result = ProductModel::getProductDetail($id);
        if (!$result) {
        	throw new ProductException();
        }
        return json($result);
    }

}
