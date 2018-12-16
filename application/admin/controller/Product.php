<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/12/10
 * Time: 21:57
 */

namespace app\admin\controller;



use app\admin\model\Image  as ImageModel;
use app\admin\model\ProductProperty  as ProductPropertyModel;
use app\admin\model\Product as ProductModel;
use app\admin\model\ProductProperty;
use app\common\lib\enum\ImageFromEnum;
use think\Db;

class Product extends Base
{
    public function index(){
        $data = input('param.');
        $query = http_build_query($data);

        $whereData = [];
        if(!empty($data['start_time']) && !empty($data['end_time']) && $data['end_time'] > $data['start_time']){
            $whereData['create_time'] = [
              ['gt',strtotime($data['start_time'])],
              ['lt',strtotime($data['end_time'])],
            ];
        }
        if(!empty($data['category_id'])){
            $whereData['category_id'] = intval($data['category_id']);
        }
        if(!empty($data['name'])){
            $whereData['name'] = ['like','%'.$data['name'].'%'];
        }

        $this->getPageAndSize($data);
        $products = model('Product')->getProByCondition($whereData ,$this->from ,$this->size);
        $total = model('Product')->getProCountByCondition($whereData);
        $pageTotal = ceil($total / $this->size);

        $category=model('Category')->all();
        $category_data=$category->hidden(['topic_img_id','description','create_time'])->toArray();

        return $this->fetch('', [
            'products' => $products,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,
            'category' => $category_data,
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'query' => $query
         ]);
    }

    public function add(){
        if(request()->isPost()){
            $data=input('post.');
            $p_p_names=explode(',',$data['property_names']);
            $p_p_details=explode(',',$data['property_details']);
            unset($data['property_names'],$data['property_details']);
            Db::startTrans();
            try{
                $ProductModel=new ProductModel();
                $res=$ProductModel->save($data);
            }catch (\Exception $e){
                Db::rollback();
                return $this->result(null,0,$e->getMessage());
            }
            if($res){
                try{
                    $ImageModel=new ImageModel();
                    $ImageModel->url = $data['main_img_url'];
                    $ImageModel->from = ImageFromEnum::LOCALHOST;
                    $ImageModel->save();

                    $res_pro=$ProductModel->save(['img_id'=>$ImageModel->id],['id'=>$ProductModel->id]);

                    $p_p_arr=[];
                    $p_name_detail=array_combine($p_p_names,$p_p_details);
                    foreach ($p_name_detail as $k=>$v){
                        $p_p_arr[]=['name'=>$k,'detail'=>$v,'product_id'=>$ProductModel->id];
                    }
                    $ProductPropertyModel = new ProductPropertyModel();
                    $ProductPropertyModel->saveAll($p_p_arr);
                }catch (\Exception $e){
                    Db::rollback();
                    return $this->result(null,0,$e->getMessage());
                }
                if($res_pro) {
                    Db::commit();
                    return $this->result(['jump_url' => url('Product/index')], 1, 'success');
                }
            }else{
                return $this->result(null,0,'新增失败');
            }
        }else{
            $category=model('Category')->all();
            $data=$category->hidden(['topic_img_id','description','create_time'])->toArray();
            return $this->fetch('',['category'=>$data]);
        }
    }
    

    //添加详情图
    public function details(){
        if(request()->isPost()){
            $data = input('param.');
            $url_arr=[];
            if(explode(",",$data['image_url'])){
                $url_arr=explode(",",$data['image_url']);
            }else{
                $url_arr[]=$data['image_url'];
            }
            Db::startTrans();
            //添加前 检查是否已存在详情图，存在则删除
            try{
                $res_PI = model('ProductImage')->getProImgByProID($data['id']);
                if($res_PI){
                    $img_id_arr=[];
                    foreach ($res_PI as $v){
                        $img_id_arr[] = $v->img_id;
                    }
                    $url_arr_by_id=model('Image')->getUrlById($img_id_arr);
                    $res=model('Image')->delByID($img_id_arr);
                    if ($res){
                        foreach ($url_arr_by_id as $v){
                            $path = ROOT_PATH.'public' . DS .'images' . $v->url;
                            if (file_exists($path)) {
                                unlink($path);//删除文件
                            };
                        }
                    }
                    model('ProductImage')->delByProID($data['id']);
                }
            }catch (\Exception $e){
                Db::rollback();
                return $this->result(null,0,$e->getMessage());
            }

            //数据入Image表
            $res=$this->saveImageUrl($url_arr,ImageFromEnum::LOCALHOST);
            //组建ProductImage表数据
            $proImage = [];
            $order = 1;
            foreach ($res as $v){
                $proImage[] = ['img_id'=>$v->id,'order'=>$order,'product_id'=>$data['id']];
                $order++;
            }

            if ($res){
                try{
                    //数据入ProductImage表
                    $res_proImg = model('ProductImage')->saveAll($proImage);
                }catch (\Exception $e){
                    Db::rollback();
                    return $this->result(null,0,$e->getMessage());
                }
                if($res_proImg){
                    Db::commit();
                    return $this->result(['jump_url' => url('Product/index')],1,"success");
                }
            }else{
                return $this->result(null,0,'操作失败');
            }
        }else{
            $id = input('param.id');
            $pro = model('Product')->getProNameByID($id);
            return $this->fetch('',['product'=>$pro]);
        }
    }

    public function edit(){
        if(request()->isPost()){
            $data=input('post.');
            if(array_key_exists('img_id',$data)){
                $img_id=$data['img_id'];
                unset($data['img_id']);
            }else{
                unset($data['img_id']);
            }
            if(array_key_exists('property_names',$data) && array_key_exists('property_details',$data)){
                $p_p_names=explode(',',$data['property_names']);
                $p_p_details=explode(',',$data['property_details']);
                unset($data['property_names'],$data['property_details']);
            }
            Db::startTrans();
            try{
                $ProductModel=new ProductModel();
                $res=$ProductModel->isUpdate(true)->save($data);
            }catch (\Exception $e){
                Db::rollback();
                return $this->result(null,0,$e->getMessage());
            }

            if($res){
                try{
                    if(isset($img_id)){
                        $ImageModel=new ImageModel();
                        $image_data=$ImageModel::get($img_id);
                        $path = ROOT_PATH.'public' . DS .'images' . $image_data->url;
                        unlink($path);
                        $image_data->delete();

                        $ImageModel->url = $data['main_img_url'];
                        $ImageModel->from = ImageFromEnum::LOCALHOST;
                        $ImageModel->save();

                        $ProductModel->save(['img_id'=>$ImageModel->id],['id'=>$ProductModel->id]);
                    }
                    if(isset($p_p_names) && isset($p_p_details)){
                        $p_p_arr=[];
                        $p_name_detail=array_combine($p_p_names,$p_p_details);
                        foreach ($p_name_detail as $k=>$v){
                            $p_p_arr[]=['name'=>$k,'detail'=>$v,'product_id'=>$ProductModel->id];
                        }
                        $ProductPropertyModel = new ProductPropertyModel();
                        $ProductPropertyModel->destroy(['product_id'=>$data['id']]);
                        $ProductPropertyModel->saveAll($p_p_arr);
                    }
                }catch (\Exception $e){
                    Db::rollback();
                    return $this->result(null,0,$e->getMessage());
                }

                Db::commit();
                return $this->result(['jump_url' => url('Product/index')], 1, 'success');
            }else{
                return $this->result(null,0,'新增失败');
            }
        }else{
            $pro_id = input('param.id');
            $pro=ProductModel::with('property')->find($pro_id);

            $category=model('Category')->all();
            $category_data=$category->hidden(['topic_img_id','description','create_time'])->toArray();
            return $this->fetch('',[
                'category'=>$category_data,
                'product'=>$pro,
            ]);
        }
    }
}