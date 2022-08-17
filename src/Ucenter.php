<?php

namespace Cyqh\Ucenter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Ucenter
{
    const INVALID_PARAM = [
        'code'=>400,
        'info'=>'参数不合法'
    ];

    protected $base_url;
    protected $app_name;
    protected $ucenter_secret;
    protected $guzzleOptions = [];

    public function __construct($base_url,$app_name,$app_secret)
    {
        $this->base_url = $base_url;
        $this->app_name = $app_name;
        $this->ucenter_secret = $app_secret;
    }
    /** 小程序登录
     * @param $code
     * @param $channel
     * @return mixed
     */
    public function miniappLogin($code,$channel){
        $url = 'login';
        $data = [
            'code'=>$code,
            'channel'=>$channel
        ];
        return $this->request("get",$url,$data);
    }

    /**根据token返回用户信息
     * @param $token
     * @return mixed
     */
    public function user($token){
        $url = 'user';
        $data = [
            'token'=>$token
        ];
        return $this->request("post",$url,$data);
    }

    /**根据user_id返回用户信息
     * @param $token
     * @return mixed
     */
    public function remoteUser($uid){
        $url = 'remote/user';
        $data = [
            'uid'=>$uid
        ];
        return $this->request("get",$url,$data);
    }

    /**根据修改个人信息返回用户信息
     * @param $token
     * @return mixed
     */
    public function userInfo($token,$nickname,$avatar){
        $url = 'user/info';
        $data = [
            'token'=>$token,
            'nickname'=>$nickname,
            'avatar'=>$avatar
        ];
        return $this->request("post",$url,$data);
    }


    /**获取积分详情
     * @param $token
     * @return mixed
     */
    public function getCoins($token){
        $url = 'coin/getNum';
        $data = [
            'token'=>$token
        ];
        return $this->request("get",$url,$data);
    }

    /**
     * 为用户操作分数
     * @param $token
     * @param string $op_type add/minus
     * @param $coin_num
     * @param $log_id
     * @param $remark
     * @return mixed
     */
    public function setCoin($token,$op_type,$coin_num,$log_id,$remark=""){
        $url = 'coin/setCoin';
        $data = [
            'token'=>$token,
            'op_type'=>$op_type,
            'coin_num'=>$coin_num,
            'log_id'=>$log_id,
            'remark'=>$remark
        ];
        return $this->request("post",$url,$data);
    }

    public function request($type,$url,$data)
    {
        $url = $this->base_url.$url;
        $data['app'] = $this->app_name;
        $data['timestamp'] = time();
        $data['non_str'] = $this->getRandom(32);
        $data['sign'] = $this->genSign($data);
        if ($type == "post"){
            try {
                $response = $this->getHttpClient()->post($url, [
                    'query' => $data,
                ])->getBody()->getContents();
            }catch (RequestException $e){
                return false;
            }

        }else{
            try {
                $response = $this->getHttpClient()->get($url, [
                    'query' => $data,
                ])->getBody()->getContents();
            }catch (RequestException $e){
                return false;
            }
        }
        return json_decode($response, true);
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    function getRandom($count): string
    {
        $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $key = "";
        for($i=0;$i<$count;$i++)
        {
            $key .= $str[mt_rand(0,32)];    //生成php随机数
        }
        return $key;
    }

    function genSign($params){
        $params['app_secret'] = $this->ucenter_secret;

        ksort($params); //对数组(map)根据键名升序排序
        $str = '';
        foreach ($params as $k => $v) {
            if ($k != "sign"){
                if ('' == $str) {
                    $str .= $k . '=' . trim($v);
                }else {
                    $str .= '&' . $k . '=' . trim($v);
                }
            }
        }
        $sign = md5($str); //此处md5值为小写的32个字符
        return $sign;
    }
}