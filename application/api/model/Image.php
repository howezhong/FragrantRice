<?php
namespace app\api\model;

use think\Model;

class Image extends BaseModel
{
	protected $hidden = ['delete_time', 'id', 'from'];

	/**
	 * 读取器拼接图片路径地址
	 * @param  [string] $value 自动读取的值
	 * @return [string]        完整图片路径地址
	 */
	public function getUrlAttr($value, $data) {
        return $this->prefixImgUrl($value, $data);
    }
}