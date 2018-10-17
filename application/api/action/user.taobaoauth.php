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
use think\Db;
use app\common\model\Users;
use app\api\model\UserToken;
/**
 * API action控制器
 * @package app\api\controller
 */
class usertaobaoauth extends Baseaction
{
    function run()
    {
        $this->checkNeedParam(['nickname'=>'传入nickname','avatar_url','openid','top_accesstoken']);
      	$login = $this->isAppLogin(false);
	        //获取请求参数
        	$nickname = $this->getRequest('nickname','');
        	$avatar_url = $this->getRequest('avatar_url','');
        	$openid = $this->getRequest('openid','');
        	$opensid = $this->getRequest('opensid','');
        	$top_accesstoken = $this->getRequest('top_accesstoken','');
        	$agent_id = AdminClientkey::getAgentId($this->getAppid());
        	$tbuser = Db::name("users_taobao_auth")->where("opensid = ? AND agent_id = ?",$opensid,$agent_id)->field("user_id")->find();
        	if(!$tbuser)
        	{
        	    //注册
        	    $r = Users::initUserByTb($nickname, $avatar_url, $openid, $opensid, $top_accesstoken, $agent_id);
        	    if($r->isSuccess())
        	    {
        	        $user = $r->getData();
        	    }else{
        	        $this->response([], $r->getErrcode(), $r->getMsg());
        	    }
        	}else{
        	    $user = Db::name("users")->find("id = ?",$tbuser['user_id'])->find();
        	    if(!$user)
        	    {
        	        $this->response([], 202, '用户数据异常');
        	    }
        	}
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