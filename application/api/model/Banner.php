<?php
namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{
	// 隐藏字段
	protected $hidden = ['update_time','delete_time'];

	// 关联模型 hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	public function items(){
		return $this->hasMany('BannerItem','banner_id','id');
	}
	
	/**
	 * 查询
	 * @param  int $id int banner所在位置
	 * @return [type]     
	 */
	public static function getBannerById($id) {
		$banner = self::with(['items','items.img'])->find($id);
		return $banner;
	}
}