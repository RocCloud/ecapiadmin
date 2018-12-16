<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 17:30
 */

namespace app\admin\controller;

use app\common\lib\IAuth;

class AdminUser extends Base
{
    public function add(){
        if(request()->isPost()){
            $data=input('post.');
            if(!is_array($data)){
                $this->result(null,0,'参数不合法');
            }
            $validate = validate('AdminUser');
            if(!$validate->check($data)){
                //$this->error($validate->getError());
                $this->result(null,0,$validate->getError());
            }

            $data['password'] = IAuth::setPassword($data['password']);
            $data['status'] = 1;
            try{
                $res = model('AdminUser')->add($data);
            }catch (\Exception $e){
                //$this->error($e->getMessage());
                $this->result(null,0,$e->getMessage());
            }
            if($res){
                //$this->success('成功');
                $this->result(null,1,'成功');
            }else{
                //$this->error('error');
                $this->result(null,0,'error');
            }
        }else{
            return $this->fetch();
        }
    }
}