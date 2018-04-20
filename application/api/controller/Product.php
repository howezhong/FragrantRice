<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;

class Product extends Base
{
    /**
     * 获取商品详情
     * @param  int $id 商品ID
     * @return json    json格式的商品数据
     */
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

    /**
     * 删除商品信息
     * @param  int $id 商品ID
     * @return boolean true/false
     */
    public function deleteOne($id='') {
        return ProductModel::destroy($id,false); 
    }


}
