<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/16
 * Time: 18:01
 */

namespace app\admin\model;


class Order extends Base
{
    protected $autoWriteTimestamp = true;
    public function Products(){
        return $this->hasMany('OrderProduct','order_id','id');
    }

    public function getOrderByCondition($condition = [], $from, $size){
        $condition['delete_time'] = [
            'exp','is null'
        ];

        $order = ['id' => 'desc'];
        return $this->where($condition)->limit($from,$size)->order($order)->select();
    }

    public function getOrderCountByCondition($condition = []){
        $condition['delete_time'] = [
            'exp','is null'
        ];
        return $this->where($condition)->count();
    }
}