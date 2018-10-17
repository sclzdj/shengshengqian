<?php
class Helper_wxclientpay
{
    private $_APPID = '';

    private $_MCHID = '';

    private $_WXKEY = '';

    private $_APPSECRET = '';

    /**
     * 生成订单号
     * @param int $prefix
     * @return string
     */
    static function get_order_id($prefix) {//目前订单号适用于1亿用户内注册用户
        $uid = substr("00000000" . $prefix, -7);
        /* 选择一个随机的方案 */
        return date('ymd', time()) . $prefix . substr(microtime(), 2, 6);
    }
    /**
     * 返回请求的域名
     * @return string
     */
    static function getRequestHost()
    {
        $http = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https://':'http://';
        $http = $http.$_SERVER['SERVER_NAME'];
        $port = $_SERVER["SERVER_PORT"]==80?'':':'.$_SERVER["SERVER_PORT"];
        $url = $http.$port;
        $host = $url.'/';
        return $host;
    }
    /**
     * 获取终端IP
     * @return Ambigous <string, unknown>
     */
    public static function get_ip()
    {
        if(isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP']<>'')
        {
            $onlineip = htmlentities($_SERVER['HTTP_X_REAL_IP']);
        }else if(isset($_SERVER['REMOTE_ADDR'])){
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }else{
            $onlineip = '127.0.0.1';
        }
        return $onlineip;
    }
    /**
     * 支付初始化
     * @param string $appid
     * @param string $mchid
     * @param string $wxkey
     * @param string $appsecret
     */
    function __construct($appid,$mchid,$wxkey,$appsecret)
    {
        $this->_APPID = trim($appid);
        $this->_MCHID = trim($mchid);
        $this->_WXKEY = trim($wxkey);
        $this->_APPSECRET = trim($appsecret);
        require_once 'wxpayapi.php';
    }
    /**
    * 统一下单 https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1
    * @param 用户openid,如果是公众号和网站下 $openid
    * @param 产品描述 $body
    * @param 产品价格，单位分 $money
    * @param 订单号 $orderid
    * @param 下单类型 APP|JSAPI $trade_type
    * @param string 回调附加数据 $callbackData
    * @param string 回调地址 $notityUrl
    * @return array('code'=>0,'prepay_id'=>$result['prepay_id'],'msg'=>''); code==0可使用prepay_id
    */
    function pay($openid,$body,$money,$orderid,$trade_type,$callbackData ,$notityUrl )
    {
        $body = trim($body);
        $money = intval($money);//单位分
        // 调用支付宝进行支付
        WxPayConfig::$APPID = $this->_APPID;
        WxPayConfig::$APPSECRET = $this->_APPSECRET;
        WxPayConfig::$KEY = $this->_WXKEY;
        WxPayConfig::$MCHID = $this->_MCHID;


//         $trade_type = 'APP';
//         $trade_type = 'JSAPI';
        $Device_info='WEB';

        //给需要提交的必须参数赋值
        $wxpay = new WxPayUnifiedOrder();
        $wxpay->SetDevice_info($Device_info);
        $wxpay->SetBody($body);
        $wxpay->SetAppid($this->_APPID);
        $wxpay->SetMch_id($this->_MCHID);
//        $order_id = Helper_Common::get_order_id(10000);
        $wxpay->SetOut_trade_no($orderid);
        $wxpay->SetTotal_fee($money);
        $wxpay->SetNotify_url($notityUrl);
        $wxpay->SetTrade_type($trade_type);
        $wxpay->SetAttach($callbackData);
        $wxpay->SetOpenid($openid);//jsjdk 传 openid
        $wxpay->SetSpbill_create_ip(self::get_ip());//终端ip

        $result =  Helper_WxPayApi::unifiedOrder($wxpay);

        if(is_array($result) && isset($result['result_code']) && $result['result_code'] == 'SUCCESS' && $result['appid'] == $this->_APPID ){
            $result = array('code'=>0,'prepay_id'=>$result['prepay_id'],'msg'=>'');
        }else{
            $result = array('code'=>4,'prepay_id'=>'','msg'=>$result['return_msg']);
        }
        return $result;
    }
    /**
     * 获取客户端支付参数
     * @param 预支付订单号 $prePayid
     * @return array()
     */
    function getClientPayInfo($prePayid)
    {
        $param = array(
            'appid'=>$this->_APPID,
            'partnerid'=>$this->_MCHID,
            'prepayid'=>$prePayid,
            'package'=>"Sign=WXPay",
            'noncestr'=>self::getNonceStr(),
            'timestamp'=>time(),
        );
        ksort($param);
        $stringA = '';
        foreach ($param as $k=>$v){
            $stringA .= sprintf("%s=%s&",$k,$v);
        }
        $stringA = trim($stringA,'&');

        $stringA = $stringA ."&key=" . $this->_WXKEY;


        $sign = strtoupper(md5($stringA));

        $param['sign']= $sign;
        $param['packagevalue'] = $param['package'];
        unset($param['package']);
        return $param;
    }

    function getJsString($info)
    {
        return 'jmw://wxpayinfo/'.base64_encode(json_encode($info));
    }
    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
}