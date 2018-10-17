<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 成都锐萌软件开发有限公司 [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\api\controller;

use think\Db;
/**
 * API action控制器
 * @package app\api\controller
 */
class usergetinfo extends Baseaction
{
    function run()
    {
      //输入你的代码
      $r = $this->isAppLogin(false);
      if(!$r->isSuccess())
      {
          $this->response([], $this->_notLoginCode, '');
      }else{
          $uid = $this->getUserId();
          $user = Db::name("users")->where("id = ?",[$uid])->field("agent_id,mid,ad_mid,mobile,username,nickname,header_ico,status,auth_mobile,score,red_balance,is_salesman,reg_time,salesman_id,bind_wx,tbauth")->find();
          if($user['header_ico']>0){
            $user['header_ico']=db('admin_attachment')->where('id',$user['header_ico'])->value('path');
          }
          $this->response($user, 200, '');
      }
    }
}