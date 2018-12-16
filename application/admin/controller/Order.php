<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/16
 * Time: 18:01
 */

namespace app\admin\controller;

use app\admin\service\Order as OrderService;

class Order extends Base
{
    public function index(){
        $data = input('param.');
        $query = http_build_query($data);

        $whereData = [];
        if(!empty($data['start_time']) && !empty($data['end_time']) && $data['end_time'] > $data['start_time']){
            $whereData['create_time'] = [
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }
        if(!empty($data['name'])){
            $whereData['snap_name'] = ['like','%'.$data['name'].'%'];
        }
        $this->getPageAndSize($data);
        $this->size = 10;
        $order = model('Order')->getOrderByCondition($whereData ,$this->from ,$this->size);
        $total = model('Order')->getOrderCountByCondition($whereData);
        $pageTotal = ceil($total / $this->size);


        return $this->fetch('', [
            'order' => $order,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'query' => $query
        ]);
    }

    //发送模板消息
    public function delivery($id){
        $data = input('param.');
        $validate = validate('IDMustBePostiveInt');
        if(!$validate->check($data)){
            $this->result(null,0,$validate->getError());
        }
        $order = new OrderService();
        $success = $order->delivery($id);
        if(array_key_exists('code',$success) && $success['code'] == 0){
            $this->result(null,$success['code'],$success['msg']);
        }else{
            $this->result(null,1,'success');
        }
    }
}