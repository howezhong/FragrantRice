<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
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
     * 根据类目ID获取该类目下所有商品(分页）
     * @param  integer  $id   商品ID
     * @param  integer $page  起始页码
     * @param  integer $size  
     * @return [type]        
     */
    public function getAllCategory($id='', $page = 1, $size = 30) {
        (new IDMustBePositiveInt())->goCheck();
        // 检测页码是否是符合规范
        (new PagingParameter())->goCheck();
        $pagingProducts = ProductModel::getProductsByCategoryID($id,true,$page,$size);
        // 该结果是数据集对象,判空要用自带的isEmpty()
        // 二维数组的直接使用empty()
        if ($pagingProducts->isEmpty()) {
            return json([
                'current_page' => $pagingProducts->currentPage(),
                'data' => []
            ]);
        }
        return json($pagingProducts);
    }
    /**
     * 删除商品信息
     * @param  int $id 商品ID
     * @return boolean true/false
     */
    public function deleteOne($id='') {
        // 校验ID
        (new IDMustBePositiveInt())->goCheck();
        return ProductModel::destroy($id);
    }


}
