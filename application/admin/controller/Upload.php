<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/10
 * Time: 21:59
 */

namespace app\admin\controller;


use think\Request;

class Upload extends Base
{
    //图片保存到本服务器
    public function uploadToLocalhost(){
        $file = Request::instance()->file('file');
        $info = $file->move(ROOT_PATH.'public' . DS .'images');
        if($info){
            $data = [
                'path' =>  str_replace('\\',"/","/".$info->getSaveName()),
                'code' => 1,
                'msg' => 'success'
            ];
            return json_encode($data);
        }
        return json_encode(['code'=>0,'msg'=>'fail']);
    }

    //图片保存到七牛云
    public function uploadToQiniu(){

    }

}