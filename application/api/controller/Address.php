<?php
namespace app\api\controller;

use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;

class Address extends Base
{
    /**
     * 更新或创建收获地址
     */
    public function createOrUpdateAddress() {
        // 参数验证
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);

    }
}