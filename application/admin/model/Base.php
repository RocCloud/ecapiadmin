<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/12
 * Time: 22:23
 */

namespace app\admin\model;


use think\Model;

class Base extends Model
{

    public function add($data){
        if (count($data) == count($data, 1)){
            return $this->allowField(true)->save($data);
        }else{
            return $this->allowField(true)->saveAll($data);
        }
    }
}