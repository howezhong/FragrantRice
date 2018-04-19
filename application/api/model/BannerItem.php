<?php
namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{
	// 隐藏字段
	protected $hidden = ['id','img_id','banner_id','update_time','delete_time'];

	// belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
	public function img() {
		return $this->belongsTo('Image','img_id','id');
	}
}
