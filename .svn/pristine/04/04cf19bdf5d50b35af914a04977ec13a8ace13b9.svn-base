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
class userchangepwd extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['password'=>'必须输入新密码!','old_password'=>'必须输入原密码!']);
      $result = $this->validate($this->_data,'UserInfo.checkpass');
      
      if(true !== $result){
          // 验证失败 输出错误信息
          $this->response([], 201, $result);
      }
      $r = $this->isAppLogin(true);
      if($r->isSuccess())
      {
          $user = $r->getData();
          if($user['password'] == md5($this->getRequest('old_password','').$user['salt']))
          {
                $update['password'] = md5(md5($this->getRequest('password','')).$user['salt']);
                Db::name("users")->where("id",$user['id'])->update($update);
                Db::name("users_token")->where("user_id = ?",$user['id'])->delete();
                
                $this->response([], 200, '密码修改成功,请重新登录!');
          }else{
                $this->response([], 202, '您输入的密码不正确,请核对后重新输入!');   
          }
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}