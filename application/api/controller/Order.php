<?php

namespace app\api\controller;

use app\api\validate\OrderPlace;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

class Order extends Base
{
    /**
     * 用户在选择商品后，向API提交包含它所选择商品的相关信息
     * API在接收到信息后，需要检查订单相关商品的库存量
     * 有库存，把订单数据存入数据中= 下单成功了，返回客户端消息，告诉客户端可以支付了
     * 调用我们的支付接口，进行支付
     * 还需要再次进行库存量检测
     * 服务器这边就可以调用微信的支付接口进行支付
     * 微信会返回给我们一个支付的结果
     * 成功：也需要进行库存量的检查
     * 成功：进行库存量的扣除，失败：返回一个失败的结果
     */
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'placeOrder']
    ];
    /**
     * 下订单(每个订单数量不固定,所以要传ID和number)
     */
    public function placeOrder() {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a'); // 不加a是获取不到products这个数组的,必须加a
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return json($status);
    }

    /**
     * 获取订单详情
     * @param $id
     * @return json
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }
        return json($orderDetail->hidden(['prepay_id']));
    }
}