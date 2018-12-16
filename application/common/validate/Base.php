<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/13
 * Time: 2:46
 */

namespace app\common\validate;


use think\Validate;

class Base extends Validate
{

    protected function isPostiveInteger($value,$rule='',$date='',$field=''){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return false;
        }
    }
}