<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/12
 * Time: 22:26
 */

namespace app\admin\model;


class Product extends Base
{
    protected $autoWriteTimestamp = true;
    public function Image(){
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function property(){
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public function getProByCondition($condition = [], $from, $size){
        $condition['status'] = [
          'neq', config('code.status_delete')
        ];

        $order = ['id' => 'desc'];

        return $this->where($condition)->limit($from,$size)->order($order)->select();
    }

    public function getProCountByCondition($condition = []){
        $condition['status'] = [
            'neq', config('code.status_delete')
        ];
        return $this->where($condition)->count();
    }

    public function getProNameByID($id){
        return $this->field('id,name')->where('id','=',$id)->find();
    }
}