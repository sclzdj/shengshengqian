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
use think\Db;
use app\common\model\Users;
use app\common\model\AdminClientkey;
/**
 * API action控制器
 * @package app\api\controller
 */
class userresetpwd extends Baseaction
{
    function run()
    {
      //输入你的代码
        $this->checkNeedParam(['username'=>'请输入手机号码','password'=>'必须输入新密码!','code_tagid'=>'请先获取验证码!','code_value'=>'请输入验证码!']);

        $check = $this->validate($this->_data, 'UserReg.checkname');
        if(true !== $check){
            $this->response([], 201, $check);
        }
            $username = trim($this->getRequest('username',''));
            $password = trim($this->getRequest('password',''));
            $code_tagid = $this->getRequest('code_tagid','');
            $code_value = $this->getRequest('code_value','');
            $agent_id = AdminClientkey::getAgentId($this->getAppid());
            $user = Db::name("users")->where("username = ? AND agent_id = ?",[$username,$agent_id])->find();
            if(!$user)
            {
                $this->response([], 202, '手机号码未注册!');
            }else{
                //验证号码
                $r = VerificationCode::verify($username, $code_tagid, $code_value);
                if(!$r->isSuccess())
                {
                    $this->response([], 203, $r->getMsg());
                }else{
                    //更新密码
                    $isChange = Db::name("users")->where("id",$user['id'])->update(['password'=>Users::makePassword($password, $user['salt'])]);
                    if($isChange)
                    {
                        $this->response([], 200, '密码重置成功!');
                    }else{
                        $this->response([], 204, '数据更新失败,请稍后重试!');
                    }
                }
            }
    }
}