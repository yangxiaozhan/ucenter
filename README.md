<h1 align="center"> ucenter </h1>

<p align="center"> ucenter sdk for chenyangqihang's wechat app.</p>


## Installing

```shell
$ composer require cyqh/ucenter
```

## Usage

```shell
注意：需要使用约定的请求地址和对应的app信息
```
#### 初始化
```shell
use Cyqh\Ucenter;

$ucenter = new Ucenter/Ucenter($url,$app)
```
#### 小程序登录
```shell
$ucenter->miniappLogin($code)
```

#### 获取用户信息
```shell
$ucenter->user($token)
```
#### 获取用户金币信息
```shell
$ucenter->getCoins($token)
```
#### 设置用户金币信息
```shell
$ucenter->setCoin($token,$op_type,$coin_num,$log_id,$remark)

op_type:minus/add
$coin_num:整数
log_id:日志id
remark：选填，备注信息

返回：操作金币，并且金币剩余信息
```

## License

MIT