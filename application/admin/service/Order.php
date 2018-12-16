<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/10/1
 * Time: 22:26
 */

namespace app\admin\service;


use app\admin\model\Order as OrderModel;
use app\common\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;

class Order
{
    protected $products;
    protected $oProducts;
    protected $uid;


    //发送模板消息
    public function delivery($orderID,$jumpPage=''){
        try{
            $order=OrderModel::where('id','=',$orderID)->find();
        }catch (\Exception $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
        if($order->status != OrderStatusEnum::PAID){
            return ['code'=>0,'msg'=>'订单未付款或已更新'];
        }
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();var_dump(2);die();
        $message = new DeliveryMessage();
        return $message->sendDeliveryMessage($order,$jumpPage);
    }
}