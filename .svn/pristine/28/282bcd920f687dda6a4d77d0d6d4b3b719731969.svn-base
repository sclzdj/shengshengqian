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

use app\common\model\Users;
use app\common\model\VerificationCode;
/**
 * API action控制器
 * @package app\api\controller
 */
class userauthmobile extends Baseaction
{
    function run()
    {
      //输入你的代码
        //限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
        $this->checkNeedParam(['username'=>'请输入要验证的号码!','password'=>'','code_tagid'=>'请先获取验证码!','code_value'=>'请输入验证码!']);
        
        //验证类,只验证号码
        $check = $this->validate($this->_data, 'UserReg.checkname');
        if(true !== $check){
            $this->response([], 201, $check);
        }
        $username = $this->getRequest('username','');
        $code_tagid = $this->getRequest('code_tagid','');
        $code_value = $this->getRequest('code_value','');
        
        
        //查找是否存在用户
        $user = Users::where("username = ?",[$username])->find();
        if(!$user)
        {
            $this->response([], 404, '号码不存在');
        }else if(intval($user['auth_mobile']) == 1){
            $this->response([], 202, '号码已经认证!');
        }else{
            //验证号码
            $r = VerificationCode::verify($username, $code_tagid, $code_value);
            if(!$r->isSuccess())
            {
                $this->response([], 203, $r->getMsg());
            }else{
                $isChange = Users::where("id = ?",[$user['id']])->update(['auth_mobile'=>1]);
                if($isChange)
                {
                    $this->response([], 200, '手机号码认证成功,您可以继续使用电话服务!');
                }else{
                    $this->response([], 204, '数据更新失败,请稍后重试!');
                }
            }
        }
    }
}