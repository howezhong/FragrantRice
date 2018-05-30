<?php
namespace app\api\controller;

use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\service\Token;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends Base
{
    /**
     * 获取用户地址信息
     * @return Json
     */
    public function getUserAddress() {
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return json($userAddress);
    }

    /**
     * 更新或创建收获地址
     */
    public function createOrUpdateAddress() {
        // 参数验证
        $validate = new AddressNew();
        $validate->goCheck();
        // 获取UID
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException([
                'code' => 404,
                'msg' => '用户收获地址不存在',
                'errorCode' => 60001
            ]);
        }
        $userAddress = $user->address;
        // 根据规则取字段是很有必要的，防止恶意更新非客户端字段
        $data = $validate->getDataByRule(input('post.'));
        if (!$userAddress ) {
            // 关联属性不存在，则新建
            $user->address()->save($data);
        } else {
            // 存在则更新
            // fromArrayToModel($user->address, $data);
            // 新增的save方法和更新的save方法并不一样
            // 新增的save来自于关联关系
            // 更新的save来自于模型
            $user->address->save($data);
        }
        throw new SuccessMessage();
    }
}