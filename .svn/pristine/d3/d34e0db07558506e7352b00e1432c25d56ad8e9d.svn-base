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
class tbhelpertopauth extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['nickname'=>'淘宝昵称','avatar_url'=>'头像地址','openid'=>'参数不足','opensid'=>'参数不足','top_accesstoken'=>'参数不足']);
      $r = $this->isAppLogin(false);
      if(!$r->isSuccess())
      {
          $this->response([], $this->_notLoginCode, '');
      }else{
          $uid = $this->getUserId();
          $openid = $this->getRequest('openid','');
          if(!$openid)
          {
              $this->response([], 201, '请确认是否授权登录成功!');
          }
          
          Db::name("users")->where("id",$uid)->update(['tbauth'=>1]);
          
          $auth = Db::name("users_taobao_auth")->where("user_id = ?",[$uid])->find();
          if(!$auth)
          {
              $log = [
                  'nickname'=>$this->getRequest('nickname',''),
                  'avatar_url'=>$this->getRequest('avatar_url',''),
                  'user_id'=>$uid,
                  'openid'=>$this->getRequest('openid',''),
                  'opensid'=>$this->getRequest('opensid',''),
                  'top_accesstoken'=>$this->getRequest('top_accesstoken','')
              ];
              Db::name("users_taobao_auth")->insert($log);
          }else{
              $log = [
                  'nickname'=>$this->getRequest('nickname',''),
                  'avatar_url'=>$this->getRequest('avatar_url',''),
                  'openid'=>$this->getRequest('openid',''),
                  'opensid'=>$this->getRequest('opensid',''),
                  'top_accesstoken'=>$this->getRequest('top_accesstoken','')
              ];
              Db::name("users_taobao_auth")->where("user_id",$uid)->update($log);
          }
          $this->response([], 200,   '');
      }
    }
}