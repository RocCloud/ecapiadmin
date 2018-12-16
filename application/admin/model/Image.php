<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/13
 * Time: 18:02
 */

namespace app\admin\model;


class Image extends Base
{
    protected $autoWriteTimestamp = true;
    protected $createTime = false;

    protected $hidden = ['delete_time','update_time'];

    public function delByID($arr){
        return $this->where('id','in',$arr)->delete();
    }

    public function getUrlById($arr){
        return $this->field('url')->where('id','in',$arr)->select();
    }
}