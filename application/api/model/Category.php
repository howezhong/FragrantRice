<?php
namespace app\api\model;

use think\Model;

class Category extends BaseModel
{
    public function img() {
    	// belongsTo('关联模型','外键','关联主键');
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

}