<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/13
 * Time: 18:58
 */

namespace app\admin\model;


class ProductImage extends Base
{
    public function Image(){
        return $this->belongsTo('Image','img_id','id');
    }

    public function delByProID($product_id){
        return $this->where('product_id','=',$product_id)->delete();
    }

    public function getProImgByProID($product_id){
        return $this->where('product_id','=',$product_id)->select();
    }
}