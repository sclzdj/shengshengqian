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
use app\common\model\AdminClientkey;
use think\Db;
use app\api\model\UserToken;
/**
 * API action控制器
 * @package app\api\controller
 */
class userwxlogin extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['code'=>'请传入微信登录成功后获取到的code']);
      $code = trim($this->getRequest('code',''));
      if(!$code)
      {
          $this->response([], 201, '请传入正确的code');
      }else{
          $agent_id = AdminClientkey::getAgentId($this->getAppid());
          $wxinfo = Users::formatAccessTokenByCode($agent_id, $code);
          if($wxinfo->isSuccess())
          {
              $data = $wxinfo->getData();
              $openid = $data['openid'];
              $unionid = $data['unionid'];
              //检查此unionid是否存在用户，存在则直接登录，不存在则查详细微信信息并且初始化账户
              $user = Db::name("user_wx")->where("app_unionid = ? AND agent_id = ?",[$unionid,$agent_id])->find();
              if(!$user)
              {
                  //新建用户
                  $wxinfo = Users::formatWxUserinfo($data['access_token'], $openid);
                  if($wxinfo->isSuccess())
                  {
                      $data = $wxinfo->getData();
                      //新建用户
                      $user = Users::initUserByWx($data,$agent_id);
                      if($user->isSuccess())
                      {
                          $user = $user->getData();
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
                                  'tbauth'=>0
                              ]
                          ];
                          $this->response($result, 200, '');
                      }else{
                          $this->response([], $user->getErrcode(), $user->getMsg());
                      }
                  }else{
                      $this->response([], $wxinfo->getErrcode(), $wxinfo->getMsg());
                  }
              }else{
                  //已经存在的用户
                  $user = Db::name("users")->where("id = ?",[$user['user_id']])->find();
                  if(!$user)
                  {
                      $this->response([], 202, '用户数据不存在!');
                  }else if(intval($user['status']) == 0)
                  {
                      $this->response([], 203, '您的账号被锁定!');
                  }else{
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
                  }
              }
          }else{
              $this->response([], $wxinfo->getErrcode(), $wxinfo->getMsg());
          }
      }
    }
}