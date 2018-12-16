<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 18:27
 */

namespace app\admin\controller;


use app\common\lib\IAuth;

class Login extends Base
{
    public function _initialize(){}

    public function index(){
        if($this->isLogin()){
            $this->redirect('Index/index');
        }
        return $this->fetch();
    }

    public function check(){
        if(request()->isPost()){
            $data = input('post.');
            $validate = validate('AdminUser');
            if(!$validate->check($data)){
                //$this->error($validate->getError());
                $this->result(null,0,$validate->getError());
            }

            if(!captcha_check($data['code'])){
                //$this->error('验证码不正确');
                $this->result(null,0,'验证码不正确');
            };

            try{
                $adminUser = model('AdminUser')->getUserByUsername($data['username']);
            }catch (\Exception $e){
                //$this->error($e->getMessage());
                $this->result(null,0,$e->getMessage());
            }

            if(!$adminUser || $adminUser->status != 1){
                //$this->error('该用户不存在');
                $this->result(null,0,'该用户不存在');
            }

            if($adminUser->password != IAuth::setPassword($data['password'])){
                //$this->error('密码错误');
                $this->result(null,0,'密码错误');
            }

            $userdata = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip()
            ];

            try{
                model('AdminUser')->save($userdata,['id'=>$adminUser->id]);
            }catch (\Exception $e){
                //$this->error($e->getMessage());
                $this->result(null,0,$e->getMessage());
            }

            session(config('admin.session_user'),$adminUser->toArray(),config('admin.session_user_scope'));
            //$this->success('成功','Index/index');
            $this->result(null,1,'success');
        }else{
            //$this->error('请求不合法');
            $this->result(null,0,'请求不合法');
        }
    }

    public function loginOut(){
        session(null,config('admin.session_user_scope'));
        $this->redirect('Login/index');
    }
}