<?php

namespace app\api\controller;

use app\api\validate\OrderPlace;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

class Order
{
    /**
     * 下订单
     */
    public function placeOrder() {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
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