<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 18:58
 */

namespace app\common\lib;


class IAuth
{
    public static function setPassword($data){
        return md5($data . config('app.password_pre_halt'));
    }
}