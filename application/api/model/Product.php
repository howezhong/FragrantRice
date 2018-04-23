<?php
namespace app\api\model;

use think\Model;

class Product extends BaseModel
{
	protected $autoWriteTimestamp = 'datetime';
    protected $hidden = [ 'delete_time', 'main_img_id', 'pivot', 'from', 'category_id', 'create_time', 'update_time'];

	// 获取图片属性
    public function imgs() {
    	return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    // 获取详情属性名称
    public function properties() {
    	return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    // 读取器拼接图片路径地址
    public function getMainImgUrlAttr($value, $data) {
        return $this->prefixImgUrl($value, $data);
    }

	public static function getMostRecent($count)
	{
		$products = self::limit($count)->order('create_time desc')->select();
		return $products;
	}
    /**
     * 获取商品详情
     * @param  [int] $id 商品ID
     * @return [type]
     */
    public static function getProductDetail($id) {
    	return self::with(['imgs' => function($query) {
    		$query->with(['imgUrl'])->order('order', 'asc');
    	}])->with('properties')->find($id);
    }

    /**
     * 获取某分类下的商品
     * @param  int  $categoryID 分类ID
     * @param  boolean $paginate  布尔值
     * @param  int $page
     * @param  integer $size
     * @return [type]
     */
    public static function getProductsByCategoryID($categoryID,$paginate=true, $page=1, $size=30) {
    	$query = self::where('category_id', '=', $categoryID);
    	if (!$paginate) {
    		return $query->select();
    	}else {
    		// paginate第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate($size, true, ['page' => $page]);
    	}
    }
}
