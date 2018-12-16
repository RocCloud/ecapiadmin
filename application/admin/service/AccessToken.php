<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/11/1
 * Time: 13:16
 */

namespace app\admin\service;



class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY ='access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url,config('wx.app_id'),config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    public function get()
    {
        $token = $this->getFromCache();
//        if(empty($token)) {
            //return $this->getFromWxServer();
        //} else {
            return $token;
        //}
    }

    //从缓存中获取access_token
    private function getFromCache() {
        $token = cache(self::TOKEN_CACHED_KEY);
        //if(empty($token)){
            //return null;
        //}
        return $token;
    }

    //从微信服务器中获取access_token
    private function getFromWxServer(){
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token,true);
        if(!$token){
            return ['code'=>0,'msg'=>'获取AccessToken异常'];
        }
        if(!empty($token['errcode'])){
            return ['code'=>0,'msg'=>$token['errcode']];
        }
        $this->saveToCache($token);
    }

    //将access_token存入缓存
    private function saveToCache($token){
        cache(self::TOKEN_CACHED_KEY,$token,self::TOKEN_EXPIRE_IN);
    }
}