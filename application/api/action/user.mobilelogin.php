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

use app\common\model\AdminClientkey;
use app\common\model\Users;
use app\api\model\UserToken;
/**
 * API action控制器
 * 
 * @package app\api\controller
 */
class usermobilelogin extends Baseaction
{

    function run()
    {
        // 输入你的代码
        $this->checkNeedParam([
            'username' => '请输入手机号码!',
            'password' => '请输入密码!'
        ]);
        // 验证类
        $check = $this->validate($this->_data, 'UserReg.checkname');
        if (true !== $check) {
            $this->response([], 201, $check);
        }
        // 获取请求参数
        $username = $this->getRequest('username');
        $password = $this->getRequest('password');
        $password = strtolower($password);
        $appid = $this->getAppid();
        $agent_id = AdminClientkey::getAgentId($appid);
        // 限制相同账号 同一天不能输入多少次？
        $user = Users::get([
            'username' => $username,
            'agent_id' => $agent_id
        ]);
        if (! $user) {
            $this->response([], 401, '用户未找到.');
        } else 
            if ($user->status == 0) {
                $this->response([], 402, '用户被锁定.');
            } else {
                $password = md5($password . $user->salt); // 客户端上传md5后的密码
                if ($password === $user->password) {
                    //登录成功
                    $token = UserToken::makeToken($user['id']);
                          $result = [
                              'token'=>$token,
                              'userinfo'=>[
                                  'mid'=>$user['mid'],
                                  'ad_mid'=>$user['ad_mid'],
                                  'mobile'=>$user['mobile'],
                                  'nickname'=>$user['nickname'],
                                  'auth_mobile'=>$user['auth_mobile'],
                                  'score'=>$user['score'],
                                  'red_balance'=>$user['red_balance'],
                                  'is_salesman'=>$user['is_salesman'],
                                  'headimgurl'=>$user['header_ico'],
                                  'bind_wx'=>$user['bind_wx'],
                                  'tbauth'=>$user['tbauth']
                              ]
                          ];
                          $this->response($result, 200, '');
                }else{
                    $this->response([], 201, '密码错误');
                }
            }
    }
}