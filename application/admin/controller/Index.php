<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 16:31
 */

namespace app\admin\controller;


class Index extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function welcome(){
        return "hello";
    }
}