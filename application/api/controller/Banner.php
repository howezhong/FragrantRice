<?php
namespace app\api\controller;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\MissException;

class Banner extends Base
{
    /**
     * 获取指定ID的banner信息
     * 
     * @param  [url]   api/banner/:id
     * @param  [http]  GET
     * @param  int $id banner id
     * @return array of banner item , code 200
     */
	public function getBanner($id) {
        // 验证ID是否合格
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        $banner = BannerModel::getBannerById($id);
        if (!$banner) {
        	throw new MissException([
        		'msg' => '请求的banner不存在',
        		'errorCode' => 40000
        	]);
        }
        return json($banner);
    }
}