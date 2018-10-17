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
use app\common\model\AdminClientkey;
/**
 * 获取验证码
 * @package app\api\controller
 */
class verifycodegetcode extends Baseaction
{
    function run()
    {
        //限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
        $this->checkNeedParam(['mobile'=>'请输入要接收验证码的号码','purpose'=>'请输入短信用途']);
        //获取请求参数
        $mobile = $this->getRequest('mobile');
        $purpose = $this->getRequest('purpose');
        //限定请求频率
        $this->limitRequestRate();
        $agent_id = AdminClientkey::getAgentId($this->getAppid());
        //查找用戶是否存在
        $user = Db::name("users")->where("username = ? AND agent_id = ?",[$mobile,$agent_id])->field("id")->find();
        
        if($purpose == 'reg' && $user)
        {
                $this->response([], 201, $mobile.'已经注册!');
        }else if($purpose == 'resetpwd' && !$user)
        {
                $this->response([], 202, $user.'号码未注册!');
        }else if($purpose == 'authmobile' && !$user)
        {
            $this->response([], 203, $user.'号码未注册!');
        }else if($purpose == 'login' && !$user)
        {
            $this->response([], 204, $user.'号码未注册!');
        }
        //产生验证码
        $result = VerificationCode::makeCode($purpose, $mobile, $this->getAppid());
        if($result->isSuccess())
        {
            $data = $result->getData();
            $r = array(
                'tagid'=>$data['tagid'],
                'exp_time'=>$data['exp_time'],
                'class_mode'=>$data['class_mode'],
                'code'=>$data['code']
            );
            $this->response($r, 200, '');
        }else{
            $this->response([], 201, $result->getMsg());
        }
    }
}
