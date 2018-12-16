<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 17:55
 */

namespace app\admin\model;



class AdminUser extends Base
{
    protected $autoWriteTimestamp = true;

    public function getUserByUsername($username){
        return $this->where('username','=',$username)->find();
    }
}