<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @param mixed
 */
function curl_get($url,&$httpCode = 0){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    //不做证书校验，部署在linux环境下请改位true
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

function curl_post($url,array $params = array()){
    $data_string = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);
    curl_setopt($ch,CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}


function geyCatName($category_id){
    if (!$category_id) {
        return '';
    }
    $categorys = config('category.lists');
    return !empty($categorys[$category_id]) ? $categorys[$category_id] : '';
}

function proStatus($id,$status){
    $controller = request()->controller();

    $sta = $status == 1 ? 0 : 1;
    $url = url($controller.'/status', ['id' => $id ,'status'=>$sta]);

    if($status == 1){
        $str = "<a href='javascript:' title='修改状态' status_url='".$url."' onclick='status(this)'><span class ='label label-success redius'>正常</span></a>";
    }elseif ($status == 0){
        $str = "<a href='javascript:' title='修改状态' status_url='".$url."' onclick='status(this)'><span class ='label label-danger redius'>待审</span></a>";
    }

    return $str;
}

function orderStatus($status){
    if($status == 1){
        $str = "<a href='javascript:' title='修改状态'><span class ='label label-warning redius'>未支付</span></a>";
    }elseif ($status == 2){
        $str = "<a href='javascript:' title='修改状态'><span class ='label label-primary redius'>已支付</span></a>";
    }elseif ($status == 3){
        $str = "<a href='javascript:' title='修改状态'><span class ='label label-primary redius'>已发货</span></a>";
    }elseif ($status == 4){
        $str = "<a href='javascript:' title='修改状态'><span class ='label label-danger redius'>异常：已支付，但库存不足</span></a>";
    }elseif ($status == 5){
        $str = "<a href='javascript:' title='修改状态'><span class ='label label-info redius'>支付超时</span></a>";
    }

    return $str;
}

function imageUrl($str){
    return config('admin.img_prefix').$str;
}