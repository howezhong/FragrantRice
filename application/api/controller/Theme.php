<?php

namespace app\api\controller;

use app\api\controller\Base;
use app\api\validate\IDCollections;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;

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
        // 获取数据
        $ids = explode(',', $ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        // 因为返回的格式数据默认是数组,所以这里把它转为数据集collection
        if (collection($result)->isEmpty()) {
            throw new ThemeException();
        }
        return json($result);
    }
    
   
}
