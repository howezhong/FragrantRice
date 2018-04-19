<?php
namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
	/**
	 * 读取器拼接图片路径地址
	 * @param  [string] $value 自动读取的值
	 * @return [string]        完整图片路径地址
	 */
	protected function prefixImgUrl($value, $data) {
		$finalUrl = $value;
		if ($data['from'] == 1) {
			$finalUrl = config('setting.img_prefix'). $value;
		}
		return $finalUrl;
	}
}