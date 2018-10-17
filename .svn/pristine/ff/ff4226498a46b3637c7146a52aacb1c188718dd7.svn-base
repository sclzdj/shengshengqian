<?php
namespace app\index\controller;
use think\Db;
class Wxpay extends Home
{
    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if(!$xml){
            return '';
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
    /**
     * 微信支付回调
     */
    public function callback()
    {
//         <xml><appid><![CDATA[wxa1e4adb858e40a20]]></appid>
//         <bank_type><![CDATA[CFT]]></bank_type>
//         <cash_fee><![CDATA[10]]></cash_fee>
//         <device_info><![CDATA[WEB]]></device_info>
//         <fee_type><![CDATA[CNY]]></fee_type>
//         <is_subscribe><![CDATA[N]]></is_subscribe>
//         <mch_id><![CDATA[1296036001]]></mch_id>
//         <nonce_str><![CDATA[o0m9osuge1yfh8hikz3peeojouhsdk4g]]></nonce_str>
//         <openid><![CDATA[os8nTvl1DsI5tRtoWvCSxvGAMzxw]]></openid>
//         <out_trade_no><![CDATA[1704131457279]]></out_trade_no>
//         <result_code><![CDATA[SUCCESS]]></result_code>
//         <return_code><![CDATA[SUCCESS]]></return_code>
//         <sign><![CDATA[DE86F0369E0C7B9D79213816D2BEFD43]]></sign>
//         <time_end><![CDATA[20170413112229]]></time_end>
//         <total_fee>10</total_fee>
//         <trade_type><![CDATA[APP]]></trade_type>
//         <transaction_id><![CDATA[4000082001201704136823851038]]></transaction_id>
//         </xml>
        $input = file_get_contents("php://input");
        if($input)
        {
            file_log(print_r($input,true),'wx.callback');
            $data = $this->FromXml($input);
            if($data == '' || !isset($data['out_trade_no']) )
            {
                $this->response('FAIL','not format');
            }
            $orderid = $data['out_trade_no'];
            $appid = $data['appid'];
            $orderinfo  = Db::name("admin_payment_config")->where("id = ?",[$orderid])->find();
            if(!$orderinfo)
            {
                $this->response('FAIL','no find order');
            }
            include_once ROOT_PATH.'lib/wxapi/wxpaycb.php';
            $wxconfig = Db::name("admin_payment_config")->where("agent_id = ? AND wx_appid = ?",[$orderinfo['agent_id'],$appid])->find();
            if(!$wxconfig)
            {
                $this->response('FAIL','not set wxpayinfo');
            }
            $mchid = $orderinfo['wx_mchid'];
            $wxkey = $orderinfo['wx_apikey'];
            $appsecret = $orderinfo['wx_appsecret'];
            $callback = new \Helper_wxpaycb();
            $callback->Handle($input,$appid,$mchid,$wxkey,$appsecret);
        }else{
            $this->response('FAIL','format error');
        }
    }
    function response($return_code,$return_msg)
    {
        $msg = '<xml>
  <return_code><![CDATA[%s]]></return_code>
  <return_msg><![CDATA[%s]]></return_msg>
</xml>';
        $msg = sprintf($msg,$return_code,$return_msg);
        exit($msg);
    }
}