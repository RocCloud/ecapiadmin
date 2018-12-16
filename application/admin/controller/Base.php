<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/8
 * Time: 19:49
 */

namespace app\admin\controller;


use think\Controller;

class Base extends Controller
{
    protected $page = '';
    protected $size = 5;
    protected $from = 0;
    protected $model = '';

    public function _initialize()
    {
        if(!$this->isLogin()){
            $this->redirect('Login/index');
        }
    }

    protected function isLogin(){
        $adminUser = session(config('admin.session_user'),'',config('admin.session_user_scope'));
        if($adminUser && $adminUser['id']){
            return true;
        }
        return false;
    }

    protected function getPageAndSize($data){
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }

    public function delete($id = 0){
        if(!intval($id)){
            return $this->result(null,0,'ID不合法');
        }

        $model = $this->model ? $this->model : request()->controller();

        try{
            $res=model($model)->save(['status'=>-1],['id'=>$id]);
        }catch (\Exception $e){
            return $this->result(null,0,$e->getMessage());
        }
        if($res){
            return $this->result(['jump_url'=>$_SERVER['HTTP_REFERER']],1,'success');
        }
        return $this->result(null,0,'删除失败');
    }

    public function status(){
        $data = input('param.');

        $validate = validate('CheckIDAndStatus');
        if(!$validate->check($data)){
            //$this->error($validate->getError());
            $this->result(null,0,$validate->getError());
        }

        $model = $this->model ? $this->model : request()->controller();

        try{
            $res=model($model)->save(['status'=>$data['status']],['id'=>$data['id']]);
        }catch (\Exception $e){
            return $this->result(null,0,$e->getMessage());
        }
        if($res){
            return $this->result(['jump_url'=>$_SERVER['HTTP_REFERER']],1,'success');
        }
        return $this->result(null,0,'删除失败');
    }

    public function saveImageUrl($url,$from){
        $image_data = [];
        if(is_array($url)){
            foreach ($url as $v){
                $image_data[]=['url'=>$v,'from'=>$from];
            }
        }else{
            $image_data = [
                'url'=>$url,
                'from'=>$from
            ];
        }
        try{
            $res = model('Image')->add($image_data);
        }catch (\Exception $e){
            return $this->result(null,0,$e->getMessage());
            Db::rollback();
        }
        return $res;
    }
}