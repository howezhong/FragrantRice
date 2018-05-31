<?php

namespace app\api\service;


use app\api\model\Product;
use app\lib\exception\OrderException;

class Order
{
    // 订单的商品列表,也就是客户端传递过来的products参数
    protected $oProducts;
    // 真实的商品信息(包括库存量)
    protected $products;
    protected $uid;
    public function place($uid,$oProducts) {
        // oProducts和Products 作对比,products从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
    }

    // 根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts) {
        // 把数据里的ID循环出来,拿到数据库去in对比
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs,$item['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }
    // 作库存量检测
    private function getOrderStatus() {
        $status = [
            'pass' => true, // 检测某一个商品数量不够就不通过
            'orderPrice' => 0, // 所有商品价格总价格
            'pStatusArray' => [] // 保存订单里所有商品的一个详细
        ];
        foreach ($this->oProducts as $oProducts) {
            $pStatus = $this->getProduceStatus($oProducts['product_id'],$oProducts['count'],$this->products);
            if (!$pStatus['haveStock']) {
               $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    private function getProduceStatus($oPID, $oCount, $products) {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0 // 某一类商品的总价格
        ];
        for ($i=0; $i<count($products);$i++) {
            if ($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        // 当传来的商品有可能根本不存在(有可能删除或者下架了)
        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => 'id为'.$oPID.'商品不存在，创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }
}