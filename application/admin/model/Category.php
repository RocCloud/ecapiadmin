<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/12
 * Time: 21:39
 */

namespace app\admin\model;



class Category extends Base
{
    protected $autoWriteTimestamp = true;
    protected $hidden = ['delete_time','update_time'];

    public static function getAll(){
        return self::select();
    }
}