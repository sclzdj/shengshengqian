<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017   [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
use think\Db;
use think\Log;
// 为方便系统核心升级，二次开发中需要用到的公共函数请写在这个文件，不要去修改common.php文件
if (! function_exists("get_order_id")) {

    /**
     * 生成订单号
     * 
     * @param int $prefix            
     * @return string
     */
    function get_order_id($prefix)
    { // 目前订单号适用于1亿用户内注册用户
        $uid = substr("00000000" . $prefix, - 7);
        /* 选择一个随机的方案 */
        return date('ymd', time()) . $prefix . substr(microtime(), 2, 6);
    }
}

/**
 * 报名退款
 */
if (! function_exists('activity_refund')) {

    /**
     * 报名退款
     * 
     * @param 报名ID|int $joinId            
     * @param 退款金额|单位分 $refund_number            
     */
    function activity_refund($joinId, $refund_money)
    {
        $refund_money = intval($refund_money);
        $limitDb = Db::table("activity_join")->where("id = ?", array(
            $joinId
        ));
        $row = $limitDb->find();
        if (empty($row)) {
            return false;
        }
        // 查找活动价格
        $activity = Db::table("activity")->where("id = ?", [
            $row['activity_id']
        ])->find();
        if (empty($activity)) {
            return false;
        }
        if (isset($row['id']) && (intval($row['id']) == $joinId) && intval($row['pay_status']) == 1) {
            if ($row['pay_method'] == 'wx') {
                if ($refund_money <= intval($row['pay_money']) * 100) {
                    $refund_orderid = get_order_id('');
                    $outTradeNo = trim($row['pay_orderid']);
                    $total_fee = intval($row['pay_money']) * 100;
                    require_once ROOT_PATH . "lib/wxapi/tp.wx.mppay.php";
                    $helper = new \tpwxmppay();
                    $helper->setAppid('');
                    $helper->setAppsecret('');
                    $helper->setMchid('');
                    $helper->setWxkey('');
                    $helper->setCert_file(ROOT_PATH . 'lib/wxapi/cert/apiclient_cert.pem');
                    $helper->setKey_file(ROOT_PATH . 'lib/wxapi/cert/apiclient_key.pem');
                    
                    $res = $helper->refundOrderByOutTradeNo($outTradeNo, $refund_orderid, $total_fee, $refund_money);
                    Log::record("activity_refund,id={$joinId},result=" . print_r($res, true), 'info');
                    if (isset($res['result_code']) && isset($res['return_code']) && $res['result_code'] == 'SUCCESS' && $res['return_code'] == 'SUCCESS') {
                        $refund_fee = intval(ceil(intval($res['refund_fee']) / 100));
                        $diff_number = ceil($refund_fee / intval($activity['need_money']));
                        // 更新报名表
                        Db::execute("UPDATE activity_join SET  refund_status = 1,refund_orderid = ?,refund_money = refund_money + ?,adult_num = adult_num - ? WHERE id = ?", [
                            $refund_orderid,
                            $refund_fee,
                            $diff_number,
                            $joinId
                        ]);
                        // 加入退款记录
                        Db::execute("INSERT INTO activity_refund(user_id,activity_id,refund_fee,refund_orderid,join_id,created) VALUES(?,?,?,?,?,?)", [
                            $row['user_id'],
                            $row['activity_id'],
                            $refund_fee,
                            $refund_orderid,
                            $row['id'],
                            time()
                        ]);
                        
                        // Db::table("activity_join")->where("id = ?",array($joinId))->update(array('refund_status'=>1,'refund_orderid'=>$refund_orderid))->setInc('refund_money',$refund_fee);
                        return $res;
                    } else {
                        return false;
                    }
                } else {
                    return false; // 超出支付的金额
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
if (! function_exists('toHashmap')) {

    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * @code php
     * $rows = array(
     * array('id' => 1, 'value' => '1-1'),
     * array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::hashMap($rows, 'id', 'value');
     *
     * dump($hashmap);
     * // 输出结果为
     * // array(
     * // 1 => '1-1',
     * // 2 => '2-1',
     * // )
     * @endcode
     *
     * 如果省略 $value_field 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     * array('id' => 1, 'value' => '1-1'),
     * array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::hashMap($rows, 'id');
     *
     * dump($hashmap);
     * // 输出结果为
     * // array(
     * // 1 => array('id' => 1, 'value' => '1-1'),
     * // 2 => array('id' => 2, 'value' => '2-1'),
     * // )
     * @endcode
     *
     * @param array $arr
     *            数据源
     * @param string $key_field
     *            按照什么键的值进行转换
     * @param string $value_field
     *            对应的键值
     *            
     * @return array 转换后的 HashMap 样式数组
     */
    function toHashmap($arr, $key_field, $value_field = null)
    {
        $ret = array();
        if ($value_field) {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row[$value_field];
            }
        } else {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row;
            }
        }
        return $ret;
    }
    if(!function_exists('cget'))
    {
        function cget($url)
        {
            $cu = curl_init();
            curl_setopt($cu, CURLOPT_URL, $url);
            curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
            $ret = curl_exec($cu);
            curl_close($cu);
            return $ret;
        }
    }
    if(!function_exists('getRequestHost'))
    {
        /**
         * 返回请求的域名
         *
         * @return string
         */
        function getRequestHost()
        {
            $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
            $http = $http . $_SERVER['SERVER_NAME'];
            $port = $_SERVER["SERVER_PORT"] == 80 ? '' : ':' . $_SERVER["SERVER_PORT"];
            $url = $http . $port;
            $host = $url . '/';
            return $host;
        }
    }
    if(!function_exists('makeSign'))
    {
        /**
         * 计算SIGN
         * @param $data
         * @param $appkey
         */
        function makeSign($data,$appkey)
        {
            ksort($data, SORT_STRING);
            $makeSign = '';
            foreach ($data as $v) {
                if (is_array($v)) {
                    $makeSign .= $v[0];
                } else {
                    $makeSign .= $v;
                }
            }
            $makeSign .= $appkey;
            $makeSign = md5($makeSign);
            return $makeSign;
        }
    }
    
    
    
    if(!function_exists('sendRequest')) {
        /**
         * 调用API请求
         * @param $action API接口名 比如：api/user/login
         * @param $data 接口参数
         * @return array json解密后的数组数据
         */
        function sendRequest($action, $data ,$token = '', $appid = '',$appkey = '')
        {
            if(!$appid)$appid = config("api_appid");
            if(!$appkey)$appkey = config("api_appkey");
            $host = getRequestHost();
            $url = $host  . $action.'?';
            $data['appid'] = $appid;
            $data['timeline'] = time();
            $data['token'] = $token;
            $data['sign'] = makeSign($data, $appkey);
            $url .= http_build_query($data);
            $result = cget($url);
            return json_decode($result, true);
        }
    }
    if(!function_exists('sendRequestUrl')) {
        /**
         * 调用API请求
         * @param $action API接口名 比如：api/user/login
         * @param $data 接口参数
         * @return 可以请求的url
         */
        function sendRequestUrl($action, $data ,$token = '', $appid = '',$appkey = '')
        {
            if(!$appid)$appid = config("api_appid");
            if(!$appkey)$appkey = config("api_appkey");
            $host = getRequestHost();
            $url = $host  . $action.'?';
            $data['appid'] = $appid;
            $data['timeline'] = time();
            $data['token'] = $token;
            $data['sign'] = makeSign($data, $appkey);
            $url .= http_build_query($data);
            return $url;
        }
    }


    if(!function_exists('getRequest')){
        /**
         * 判断 $_RUQUEST 是否接收到$key参数
         * @param $key
         * @param bool $default 默认值
         * @return bool|string
         */
       function getRequest($key,$default=false)
       {
           if(isset($_REQUEST[$key]) && trim($_REQUEST[$key])){
               return trim($_REQUEST[$key]);
           }else{
               return $default;
           }
       }
    }
    
    if(!function_exists('file_log'))
    {
        function file_log($msg,$file = 'common')
        {
            $filePath = RUNTIME_PATH . DS . 'log' . DS . date('Ymd') . DS;
            if (is_dir($filePath)) {
                if (! is_writable($filePath)) {
                    return false;
                }
            } else {
                @mkdir($filePath, 0700);
            }
            $filePath .= $file.'.log';
            $content = date('Y-m-d H:i:s') . "\t" . $msg . "\r\n\r\n";
            @file_put_contents($filePath, $content, FILE_APPEND);
        }
    }


}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
if (! function_exists('random')) {
    function random($length=4, $type='all', $convert=0){
        $config = array(
            'number'=>'1234567890',
            'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'small'=>'abcdefghijklmnopqrstuvwxyz',
            'big'=>'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
            'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );
        
        if(!isset($config[$type])) $type = 'string';
        $string = $config[$type];
        
        $code = '';
        $strlen = strlen($string) -1;
        for($i = 0; $i < $length; $i++){
            $code .= $string{mt_rand(0, $strlen)};
        }
        if(!empty($convert)){
            $code = ($convert > 0)? strtoupper($code) : strtolower($code);
        }
        return $code;
    }
}


//字符串连接
if(!function_exists('str_linked')){
    function str_linked($str1='',$str2='')
    {
        return $str1.$str2;
    }
}

//取数组每个键值
if(!function_exists('array_v')){
    function array_v($key,$arr=array())
    {
        return $arr?(array_key_exists($key,$arr)?$arr[$key]:$arr[0]):'';

    }
}
//规则分类
if(!function_exists('myRuleCategory')){
    function myRuleCategory($arr,$parent_id=0,$l=1,$pid='parent_id',$id='id',$level='level'){
        $result=[];
        //循环判断数据
        foreach ($arr as $k => $v) {
            if($parent_id==$v[$pid]){
                //递归1
                $v[$level]=$l;
                $result[]=$v;
                $result=array_merge($result,myRuleCategory($arr,$v[$id],$l+1,$pid,$id,$level));
            }
        }
        return $result;
    }
}
if(!function_exists('get_split'))
{
    /**
     *
     *2011-12-30-下午05:30:52 by 460932465
     * 返回格式化后的字符串
     * @param 左边字符 $leftchar
     * @param 右边字符 $rightchar
     * @param 完整字符串 $string
     */
    function get_split($leftchar,$rightchar,$string)
    {
        $a =  strpos($string,$leftchar) ;
        $b = strpos($string,$rightchar,$a);
        if($a === false && $b === false)
        {
            return false;
        }else
        {
            $c = substr($string,$a + strlen($leftchar),$b - $a - strlen($leftchar));
            $c = str_replace("\n","",$c);
            $c = str_replace("\r","",$c);
            $c = str_replace("\r\n","",$c);
            return $c;
        }
    }
}

if(!function_exists('G_CF')){
    function G_CF($name){
       $config=db('config')->where('name',$name)->find();
       if(!$config) return false;
       if($config['type']=='image') return db('admin_attachment')->where('id',$config['val'])->value('path');
       if($config['type']=='view') return 'index/appweb/news/id/'.$config['id'];
       return $config['val'];
    }
}

