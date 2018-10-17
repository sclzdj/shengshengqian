<?php
require_once 'WxPay.Data.php';
require_once 'wxpayapi.php';

use think\Db;
use app\common\model\Users;
use app\common\model\UsersBills;
/**
 * 微信支付回调接口
 *s
 * @return string 返回成功或者失败。
 */

//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
//require_once './wxlib/wxcallback.php';
class Helper_wxpaycb extends WxPayNotifyReply
{
    /**$param 参数 查看 https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_7&index=3
     * 覆盖成功回调函数
     * @see WxPayNotifyDiy::succCallback()
     */
    function succCallback($param)
    {
        $orderid = trim($param['out_trade_no']);
        //业务逻辑处理,自定义回调参数
        $orderinfo  = Db::name("users_orders")->where("id = ?",[$orderid])->find();
        if(!$orderinfo)
        {
            return;
        }else if(intval($orderinfo['total_price']*100) <> intval($param['total_fee']))
        {
            return ;//價格不一致
        }
        else{
            if(in_array([0,2], intval($orderinfo['callback_status'])))
            {
                //標記處理中
                Db::name("users_orders")->where("id = ?",[$orderid])->update(['callback_time'=>time(),'callback_status'=>1,'pay_status'=>99]);
                //未處理、處理失敗
                $class_mode = $orderinfo['class_mode'];
                $class_param = $orderinfo['class_param'];
                $buyer_name = $orderinfo['buyer_name'];
                $agent_id = $orderinfo['agent_id'];
                
                if($class_mode == 'payment')
                {
                    $paypro = Db::name("admin_pay_product")->where("id = ?",$class_param)->find();
                    if(!$paypro)
                    {
                        //產品不存在
                        Db::name("users_orders")->where("id = ?",[$orderid])->update(['callback_status'=>2]);
                        return;
                    }else{
                        //查找出充值人的Mid 
                        $payuser = Db::name("users")->where("id = ?",[$orderinfo['user_id']])->field("id,mid")->find();
                        if(!$payuser)
                        {
                            Db::name("users_orders")->where("id = ?",[$orderid])->update(['callback_status'=>2]);
                            return ;
                        }
                        
                        //查看用戶是否存在
                        $user = Db::name("users")->where("username = ? AND agent_id = ?",$buyer_name,$agent_id)->find();
                        if(!$user)
                        {
                           
                            //用戶不存在，新建
                            $reg = Users::payment_InitUser($buyer_name, $agent_id,$payuser['mid']);
                            if($reg->isSuccess())
                            {
                                //創建call_account
                                $data = $reg->getData();
                                $user_id  = $data['user_id'];
                                $call_account = [
                                    'user_id'=>$user_id,
                                    'balance'=>$paypro['arrival_money'],
                                    'reg_day'=>date('Y-m-d'),
                                    'rategroup_id_a'=>$paypro['rategroup_id_a'],
                                    'rategroup_id_b'=>$paypro['rategroup_id_b'],
                                    'gatewaygroup_id_a'=>$paypro['gatewaygroup_id_a'],
                                    'gatewaygroup_id_b'=>$paypro['gatewaygroup_id_b'],
                                ];
                                Db::name("users_call_account")->insert($call_account);
                            }else{
                                Db::name("users_orders")->where("id = ?",[$orderid])->update(['callback_status'=>2]);
                            }
                        }else{
                            //給現有用戶充值
                            $sql = "UPDATE ".config("prefix")."users_call_account SET balance+= ? ,rategroup_id_a = ?,rategroup_id_b = ?,gatewaygroup_id_a = ?,gatewaygroup_id_b = ? WHERE user_id = ?";
                            Db::execute($sql,[$paypro['arrival_money'],$paypro['rategroup_id_a'],$paypro['rategroup_id_b'],$paypro['gatewaygroup_id_a'],$paypro['gatewaygroup_id_b']]);
                            //添加賬戶話費記錄
                            UsersBills::addLog($user['id'], $paypro['arrival_money'], $orderinfo['body'], 'payment');
                            //更新狀態成功
                            Db::name("users_orders")->where("id = ?",[$orderid])->update(['callback_status'=>99]);
                        }
                    }
                }else if($class_mode == 'vip'){
                    
                }
            }else{
                //處理中 或者處理成功
            }
        }
            
    }

    /**
     * 失败时 回调
     * @param array $param
     */
    function errCallback($param)
    {
        
    }


    /**
     *
     * 回调入口
     * @param bool $needSign  是否需要签名输出
     * @param array $config
     */
    final  function Handle($xml,$appid,$mchid,$wxkey,$appsecret)
    {

        $msg = "OK";
        $callarray = $this->FromXml($xml);

        if(is_array($callarray) && count($callarray) && isset($callarray['attach'])){
            $attach = json_decode($callarray['attach'],true);

            WxPayConfig::setconfig($appid,$mchid,$wxkey,$appsecret);

            $result = Helper_WxPayApi::notify(array($this, 'NotifyCallBack'), $msg,$xml);
            //             print_r($callarray);die;
            //验证成功,写逻辑


            if($result == true){

                // 商户订单号
                $out_trade_no = $callarray['out_trade_no'];
                // 交易号
                $trade_no = $callarray['transaction_id'];
                // 交易状态
                $trade_status = $callarray['return_code'];
                //充值现金
                $pay_fee = floatval($callarray['cash_fee']/100);
                //dump($pay_fee);die;
                $notify_time = time();
                if($result == false){
                    $this->errCallback($callarray);
                    $this->SetReturn_code("FAIL");
                    $this->SetReturn_msg($msg);
                    $this->ReplyNotify(false);
                    return;
                } else {

                    $this->succCallback($callarray);
                    //该分支在成功回调到NotifyCallBack方法，处理完成之后流程
                    $this->SetReturn_code("SUCCESS");
                    $this->SetReturn_msg("OK");
                }
                $this->ReplyNotify();
            }else{
                $this->SetReturn_code("FAIL");
                $this->SetReturn_msg($msg);
                $this->ReplyNotify(false);
            }
        }

    }

    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        //TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
        return true;
    }

    /**
     *
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param array $data
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    final public function NotifyCallBack($data)
    {
        return true;

    }

    /**
     *
     * 回复通知
     * @param bool $needSign 是否需要签名输出
     */
    final private function ReplyNotify()
    {

        //如果需要签名
        if( $this->GetReturn_code() == "SUCCESS")
        {
            $str= "<xml>
		          <return_code><![CDATA[SUCCESS]]></return_code>
		          <return_msg><![CDATA[OK]]></return_msg>
		          </xml>";
        }else{

            $str=  "<xml>
		          <return_code><![CDATA[FAIL]]></return_code>
		          <return_msg><![CDATA['签名失败']]></return_msg>
		          </xml>";
        }
        Helper_WxPayApi::replyNotify($str);

    }
}

