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

namespace app\api\controller;
use think\Cache;
use think\Db;
use app\api\model;
use app\api\model\Common;
use app\api\model\UserToken;

/**
 * 前台首页控制器
 * @package app\api\controller
 */
class Login extends Baseaction
{
    
    /**
    * 此接口暂时只做演示API接口使用
    * @date: 2017年3月9日 下午5:20:22
    * @author: cnsanshao(460932465@qq.com)
    * @param: username password
    * @return:
    */
    public function login()
    {
        //限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
        $this->checkNeedParam(['username'=>'请输入用户名','password'=>'请输入密码']);
        //获取请求参数
        $username = $this->getRequest('username');
        $password = $this->getRequest('password');
        $user     = Db::name('users')->where('username = ?',[$username])->find();
        if(is_null($user))
        {
            //not find username
            $this->response([], 401);
        }else if(md5($password.$user['salt']) === $user['password'])
        {
            //password verify
            if(intval($user['status']) == 0)
            {
                //user lock
                $this->response([], 601);
            }else{
                //login success 1.add token 2.update login info
                $token = UserToken::makeToken($user['id']);
                Db::name("users_detail")->where("user_id = ?",[$user['id']])->update(['last_login_ip'=>get_client_ip(),'last_login_time'=>time()]);
                $this->response(['token'=>$token], 200, 'loginok');
            }
        }else{
            //password error
            $this->response([], 602);
        }
    }
}
