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

use app\common\model\VerificationCode;
use app\common\model\Users;
use app\common\model\AdminClientkey;
/**
 * API action控制器
 * @package app\api\controller
 */
class usermobilereg extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['username'=>'请输入注册号码!','password'=>'请输入注册密码!','code_tagid'=>'请先获取验证码','code_value'=>'请输入验证码']);
      //验证类
      $check = $this->validate($this->_data, 'UserReg');
      if(true !== $check){
          $this->response([], 201, $check);
      }
      //获取请求参数
      $username = $this->getRequest('username');
      $password = $this->getRequest('password');
      $tagid = $this->getRequest('code_tagid');
      $code = $this->getRequest('code_value');
      $agent_id = AdminClientkey::getAgentId($this->getAppid());
      //检查 验证码
      $r = VerificationCode::verify($username, $tagid, $code);
      if($r->isSuccess())
      {
      
          $user = Users::initUser($username, $password, $agent_id,true);
          if($user->isSuccess())
          {
              $this->response([], 200, '');
          }else{
              $this->response([], 201, $user->getMsg());
          }
      }else{
          $this->response([], 202, $r->getMsg());
      }
    }
}