<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 17:38
 */

namespace app\common\validate;



class AdminUser extends Base
{
    protected $rule = [
        'username' => "require|max:20",
        'password' => "require|max:20"
    ];
}