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

namespace app\index\controller;

use think\auto\makeapi;
use app\common\model\Users;
use app\common\model\UserRedpackage;

use app\api\model\UserToken;
use app\admin\controller\Config;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{
    function response($code,$msg)
    {
        exit(json_encode(['code'=>$code,'msg'=>$msg]));
    }
    public function mqtt()
    {
        
        if($this->request->isPost())
        {
            $topic = $this->request->post("topic","");
            $msg = $this->request->post("msg","");
            if($topic && $msg)
            {
                include_once ROOT_PATH.'/lib/mqtt/phpMQTT.php';
                $mqtt = new \phpMQTT("192.168.0.196", 1883, "phpMQTT Pub Example"); //Change client name to something unique
                
                if ($mqtt->connect()) {
                    
                    $mqtt->publish($topic,$msg,0,0);
//                     $mqtt->publish('$client/135b4e41-324c-4f8e-b0a6-366d31542a15/user/1','',0,1);
//                     $mqtt->publish("135b4e41-324c-4f8e-b0a6-366d31542a15/agent/-6917529027641081071",'',0,1);
//                     $mqtt->publish("135b4e41-324c-4f8e-b0a6-366d31542a15/user/1",'',0,1);
//                     $mqtt->publish("135b4e41-324c-4f8e-b0a6-366d31542a15/agent/1",'',0,1);
                    $mqtt->close();
                }
                $this->response(200, '发送成功');
            }else{
                $this->response(1, '参数不足');
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 添加API action文件
     * @param int $a 文字参数说明
     */
    public function addaction()
    {
//         $bizcontent = "{\"body\":\"我是测试数据\","
//             . "\"subject\": \"App支付测试\","
//                 . "\"out_trade_no\": \"20170125test01\","
//                     . "\"timeout_express\": \"30m\","
//                         . "\"total_amount\": \"0.01\","
//                             . "\"product_code\":\"QUICK_MSECURITY_PAY\""
//                                 . "}";
//         dump(json_decode($bizcontent,true));
        //自动生成API 请求ACTION
//         makeapi::action('login', 'signin');
//         makeapi::action('contactbackup', 'uploaddata');
//         makeapi::action('refundshop', 'getdata');
//         makeapi::action('user', 'mobilereg');
//         makeapi::action('user', 'mobilelogin');
//         makeapi::action('exchange', 'product');
<<<<<<< .mine
          //makeapi::action('exchange', 'submit');//虚拟提交
||||||| .r26
        makeapi::action('exchange', 'submit');//虚拟提交
=======
        makeapi::action('tbhelper', 'getcart');//虚拟提交
//         makeapi::action('tbhelper', 'upcart');//虚拟提交
//         makeapi::action('tbhelper', 'upmyorder');//虚拟提交
        
>>>>>>> .r114
//         makeapi::action('exchange', 'submit1');//实物提交
         // makeapi::action('user', 'banklist');
         //makeapi::action('user', 'editwithdrawtype');
         //makeapi::action('user', 'delwithdrawtype');
        //makeapi::action('user', 'withdrawtype');
         /*makeapi::action('user', 'collection');
         makeapi::action('user', 'addcollection');
         makeapi::action('user', 'delcollection');*/
         //makeapi::action('user', 'withdrawalscard');
         //makeapi::action('user', 'scorelog');
         //makeapi::action('user', 'redbalancelog');
         //makeapi::action('taoke', 'iconurl');
         // makeapi::action('user', 'withdraw');
          //makeapi::action('user', 'taobaoauth');
          //makeapi::action('user', 'updateinfo');
           /*makeapi::action('user', 'selectwithdraw');
          makeapi::action('user', 'cancelwithdraw');*/
          //makeapi::action('taoke', 'configview');
          //makeapi::action('taoke', 'appload');
          makeapi::action('user', 'teamlevel');
          makeapi::action('user', 'team');
            // makeapi::action('user', 'addwithdrawtype');
            //makeapi::action('user', 'team');
//         makeapi::action('user', 'getrank');
//            makeapi::action('telephone', 'callback');
//         makeapi::action('onecall', 'setdata');
//         makeapi::action('call', 'callback');
    }
    public function index()
    {
        /*header("Content-type: text/html; charset=utf-8");
        $result = sendRequest('api/taoke/browsinghistory',[],'950d74e3-8813-a47b-f416-1d355992e86b',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        dump($result);die;
        
//         die;
//         dump(md5(md5("123456789")."164509"));die;
 //发送红包
//                 $redpack = new UserRedpackage();
//                 $redpack->setTitle('系统红包');
//                 $redpack->setRemark('注册有礼送红包!');
//                 $redpack->setRed_class('reg');
                
//                 $redpack->sendRedPack(24, 5000, 180);
//                 die;
        
        $result = sendRequest('api/tbhelper/getcart',[],'950d74e3-8813-a47b-f416-1d355992e86b',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        dump($result);
        die;*/
        //distributionrollnotification
//         $result = sendRequestUrl('api/distribution/rollnotification',[],'86d83419-b6bd-8c26-4550-dfac0824954e',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         echo json_encode([['n'=>'张三','v'=>'18080850975'],['n'=>'李4','v'=>'18080851111']]);die;
//         $result = sendRequestUrl('api/contactbackup/uploaddata',['data'=>json_encode([['n'=>'张三','v'=>'18080850975'],['n'=>'李4','v'=>'18080851111']]),'batch_id'=>time(),'page'=>1],'86d83419-b6bd-8c26-4550-dfac0824954e',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/telephone/callback',["callerd"=>'18080850897'],'86d83419-b6bd-8c26-4550-dfac0824954e',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/order/create',['buyer_name'=>'18080850898','class_mode'=>'vip','class_param'=>1],'91c3901c-bf19-94a1-d0b8-63735d4aaa59',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/team',[],'91c3901c-bf19-94a1-d0b8-63735d4aaa59',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/distribution/myposter',[],'91c3901c-bf19-94a1-d0b8-63735d4aaa59',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $s = UserToken::getUserId('123456');
//         $s = Users::get(1);
//         dump($s);
//         dump(PHP_OS);
//         $user = Users::get(1);
//         $user = new Users();
//         $user->username = time();
//         $salt = mt_rand(100000, 999999);
//         $user->password = md5(time().$salt);
//         $user->salt = $salt;
//         $user->save();
//         dump($user->UsersCallAccount);
//         $result = sendRequestUrl('api/login/reg',['username'=>'18080850897','password'=>'123456','code_tagid'=>1489732958,'code_value'=>858100],'',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/contactbackup/synctolocal',[],'86d83419-b6bd-8c26-4550-dfac0824954e',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/login/signin',['username'=>'18080850897','password'=>md5('123456')],'',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/onecall/getdata',[],'123456',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/addwithdrawtype',['type'=>'2','card_name'=>'杜生','card_num'=>'12345678945612322','bank_id'=>'3','bank_address'=>'中国四川成都','alipay'=>'18353621791','alipay_name'=>'杜生'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/editwithdrawtype',['id'=>'2','card_name'=>'杜生1','card_num'=>'12345678945612322','bank_id'=>'3','bank_address'=>'中国四川成都','alipay'=>'18353621791','alipay_name'=>'杜生1'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/delwithdrawtype',['id'=>'2'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/withdrawtype',['id'=>'0'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         $result = sendRequestUrl('api/user/collection',[],'1e14c185-73e2-12e6-ad9e-3b49006631d6
',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/addcollection',['id'=>'20011'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/delcollection',['id'=>'2,4'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/withdrawalscard',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/scorelog',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        /*$result = sendRequestUrl('api/user/redbalancelog',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');*/
        //$result = sendRequestUrl('api/taoke/iconurl',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/selectwithdraw',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
      // $result = sendRequestUrl('api/user/cancelwithdraw',['id'=>1],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/taoke/configview',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/taoke/appload',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/team',['level'=>2,'page'=>1,'pageSize'=>10],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/teamlevel',[],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/taobaoauth',['nickname'=>'伪忆','avatar_url'=>'sfkje','openid'=>'fjewkfwk','opensid'=>'sadffklewf','top_accesstoken'=>'ekfwegergklerk1'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/updateinfo',['header_ico'=>'1','nickname'=>'lekwf'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        //$result = sendRequestUrl('api/user/banklist',[],'',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/feedback',['content'=>'需要改进'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/feedback',['content'=>'需要改进'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/user/updatepass',['username'=>'18353621790','old_password'=>'123456','new_password'=>'1234567'],'0f6b2f43-ca0f-003b-547e-ce98319be407',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/user/mobilelogin',['username'=>'18353621790','password'=>md5('1234567')],'',1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/user/mobilereg',['username'=>'18353621790','password'=>'123456','code_tagid'=>1495781721,'code_value'=>625577],'',1000,'8801eedd308f5e60ff471eb63bce9ffe');
         //$result = sendRequestUrl('api/verifycode/getcode',['mobile'=>'18353621790','purpose'=>'reg'],1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         $result = sendRequestUrl('api/onecall/setdata',['index'=>mt_rand(1, 9),'name'=>mt_rand(1000, 9999),'callerd'=>time()],'123456',1000,'8801eedd308f5e60ff471eb63bce9ffe');
        
        echo($result);
//         $result = sendRequest('api/call/callback',['mobile'=>'18080850898','purpose'=>'login'],1000,'8801eedd308f5e60ff471eb63bce9ffe');
//         dump($result);
    }
    
}
