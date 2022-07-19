<?php

namespace Cyqh\Ucenter;

use GuzzleHttp\Client;

class Ucenter
{
    const INVALID_PARAM = [
        'code'=>400,
        'info'=>'参数不合法'
    ];

    protected $base_url;
    protected $app_name;
    protected $guzzleOptions = [];

    public function __construct($base_url,$app_name)
    {
        $this->base_url = $base_url;
        $this->app_name = $app_name;
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
        $url = 'coin/getNum';
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
        if ($type == "post"){
            $response = $this->getHttpClient()->post($url, [
                'query' => $data,
            ])->getBody()->getContents();
        }else{
            $response = $this->getHttpClient()->get($url, [
                'query' => $data,
            ])->getBody()->getContents();
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
}