<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;
use app\api\validate\Count;
use app\lib\exception\ThemeException;

class Product extends Base
{
    /**
     * 根据类目ID获取该类目下所有商品(分页)
     * @url /product?id=:category_id&page=:page&size=:page_size
     * @param  integer $id   商品ID
     * @param  integer $page  分页页数(可选)
     * @param  integer $size  每页数目(可选)
     * @return json
     */
    public function getByCategory($id='', $page = 1, $size = 30) {
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
        $data = $pagingProducts->hidden(['summary'])->toArray();
        // 如果是简洁分页模式，直接序列化$pagingProducts这个Paginator对象会报错
        // $pagingProducts->data = $data;
        return json([
            'current_page' => $pagingProducts->currentPage(),
            'data' => $data
        ]);
    }

    /**
     * 获取某分类下全部商品(不分页)
     * @url /product/all?id=:category_id
     * @param int $id 分类id号
     * @return \think\Paginator
     * @throws ThemeException
     */
    public function getAllInCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id, false);
        if ($products->isEmpty()) {
            throw new ThemeException();
        }
        $data = $products->hidden(['summary']);
        return json($data);
    }

    /**
     * 获取商品详情
     * 如果商品详情信息很多，需要考虑分多个接口分布加载
     * @url /product/:id
     * @param  int $id 商品ID
     * @return json    json格式的商品数据
     */
    public function getOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        // 获取商品详情
        $result = ProductModel::getProductDetail($id);
        if (!$result) {
         throw new ProductException();
        }
        return json($result);
    }

    /**
     * 获取指定数量的最新商品,不过为了减少服务器压力,给予限制最高多少条
     * @url /product/recent?count=:count
     * @param  int $count 条数
     * @return
     */
    public function getRecent($count=15) {
        // 如果不传count是15条,如果传了?count= 为空,那就会查出所有的,Bug
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return json($products);
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
