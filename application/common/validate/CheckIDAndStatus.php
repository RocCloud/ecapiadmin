<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/13
 * Time: 2:40
 */

namespace app\common\validate;


class CheckIDAndStatus extends Base
{
    protected $rule = [
        'id' => "require|isPostiveInteger",
        'status' => "require|in:-1,0,1"
    ];
}