<?php
namespace app\api\model;

use think\Model;

class ProductImage extends BaseModel
{
	protected $hidden = ['img_id', 'delete_time', 'product_id'];
	public function imgUrl() {
		// belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型')
		return $this->belongsTo('Image', 'img_id', 'id');
	}
}
