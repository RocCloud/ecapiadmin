<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/11/1
 * Time: 12:48
 */

namespace app\admin\service;


use app\admin\model\User as UserModel;

class DeliveryMessage extends WxMessage
{
    //使用的模板消息 id
    const DELIVERY_MSG_ID = 'sc9ZhwZ_ejgAEZNfpL5Us1PZguD06_rFycW4Ysktooc';

    public function sendDeliveryMessage($order,$tplJumpPage = ''){
        var_dump(2);die();
        if(!$order){
            return ['code'=>0,'msg'=>'订单不存在，请检查ID'];
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord = 'keyword2.DATA'; var_dump(1);die();
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order){
        $dt = new \DateTime();
        $data=[
            'keyword1' => [
              'value' => '顺丰速运'
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#274088'
            ],
            'keyword3' => [
                'value' => $order->order_no
            ],
            'keyword4' => [
                'value' => $dt->format("Y-m-d H:i")
            ],
        ];
        $this->data =$data;
    }

    private function getUserOpenID($uid){
        try{
            $user = UserModel::get($uid);
        }catch (\Exception $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
        if (!$user) {
            return ['code'=>0,'msg'=>'用户不存在'];
        }
        return $user->openid;
    }
}