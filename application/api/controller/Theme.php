<?php

namespace app\api\controller;

use app\api\controller\Base;
use app\api\validate\IDCollections;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;
use app\api\validate\IDMustBePositiveInt;

class Theme extends Base
{
    /**
     * 获取主题精选
     * @param  url?ids=id1,id2,id3...
     * @return 
     */
    public function getSimpleList($ids='')
    {
        // 校验
        (new IDCollections())->goCheck();
        
        $ids = explode(',', $ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        // 因为返回的格式数据默认是数组,所以这里把它转为数据集collection
        if (collection($result)->isEmpty()) {
            throw new ThemeException();
        }
        return json($result);
    }
    
    /**
     * 获取该主题下的所有商品
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getComplexOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if(!$theme){
            throw new ThemeException();
        }
        return json($theme->hidden(['products.summary']));
    }
}
