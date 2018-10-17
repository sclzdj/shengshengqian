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

namespace app\common\model;

use think\Model;
use think\fn\Result;
use app\admin\model\Config;
use think\Db;
/**
 * 公共模型
 * @package app\common\model
 */
class Users extends Model
{
    
   
    protected static function init()
    {
        Users::event('after_insert', function ($user) {
            //新建用户数据时，做HOOK操作
        });
    }
    /**
     * 用户详情
     */
    public function UsersDetail()
    {
        return $this->hasOne('UsersDetail','user_id');
    }
    /**
     * 用户电话服务属性
     */
    public function UsersCallAccount()
    {
        return $this->hasOne('UsersCallAccount','user_id','id');
    }
    /**
     * 用户分销信息
     */
    public function UsersShareinfo()
    {
        return $this->hasOne('UsersShareinfo','user_id');
    }
    
    /**
     * 用户绑定的微信，可多条
     */
    public function UsersWx()
    {
        return $this->hasMany('UsersWx','user_id');
    }
    /**
     * 用户一键拨号记录，多条
     */
    public function oneCalls()
    {
        return $this->hasMany('OnecallUserlog','user_id');
    }
    /**
     * 用户套餐
     */
    public function packages()
    {
        return $this->hasMany('UsersCallPackages','user_id');
    }
    /**
     * 转换openid到用户信息
     * @param string $access_token
     * @param string $openid
     * @return boolean|mixed
     * @package {
   "subscribe": 1, 
   "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
   "nickname": "Band", 
   "sex": 1, 
   "language": "zh_CN", 
   "city": "广州", 
   "province": "广东", 
   "country": "中国", 
   "headimgurl":  "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4
eMsv84eavHiaiceqxibJxCfHe/0",
  "subscribe_time": 1382694957,
  "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
  "remark": "",
  "groupid": 0,
  "tagid_list":[128,2]
}
     */
    public static function formatWxUserinfo($access_token,$openid)
    {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s";
            $url = sprintf($url,$access_token,$openid);
            $result = cget($url);
            $result = json_decode($result,true);
            file_log($url."\r\n".print_r($result,true),'wx.app.formatWxUserinfo');
            if(isset($result['errcode']) || !isset($result['openid']))
            {
                return new Result(201, [], '');
            }else{
                //success
                return new Result(200, $result, '');
            }
    }
    /**https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
     * 转换code到 access_token \ openid
     * @param string $code
     * @return { 
"access_token":"ACCESS_TOKEN", 
"expires_in":7200, 
"refresh_token":"REFRESH_TOKEN",
"openid":"OPENID", 
"scope":"SCOPE",
"unionid":"o6_bmasdasdsad6_2sgVt7hMZOPfL"
}
     */
    public static function formatAccessTokenByCode($agent_id,$code)
    {
        $agent_id = intval($agent_id);
        $wxconfig = AdminWxconfig::getWXconfig($agent_id);
        if($wxconfig->isSuccess())
        {
            $config = $wxconfig->getData();
            $appid = $config['app_appid'];
            $secret = $config['app_appsecret'];
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
            $url = sprintf($url,$appid ,$secret,$code);
            $result = cget($url);
            $result = json_decode($result,true);
            file_log($url."\r\n".print_r($result,true),'wx.app.formatAccessTokenByCode');
            if(isset($result['errcode']) || !isset($result['openid']))
            {
                return new Result(201, [], '');
            }else{
                return new Result(200, $result, '');
            }
        }else{
            return $wxconfig;
        }
    }
    /**
     * 获取APP微信登录信息 根据code
     * @param int $agent_id
     * @param string $code
     * @package {
   "subscribe": 1, 
   "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
   "nickname": "Band", 
   "sex": 1, 
   "language": "zh_CN", 
   "city": "广州", 
   "province": "广东", 
   "country": "中国", 
   "headimgurl":  "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4
eMsv84eavHiaiceqxibJxCfHe/0",
  "subscribe_time": 1382694957,
  "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
  "remark": "",
  "groupid": 0,
  "tagid_list":[128,2]
}
     */
    public static function getWxinfoByCode($agent_id,$code)
    {
        $token = self::formatAccessTokenByCode($agent_id, $code);
        if($token->isSuccess())
        {
            $data = $token->getData();
            return self::formatWxUserinfo($data['access_token'], $data['openid']);
        }else{
            return $token;
        }
    }
    
    /**
     * 注册一个账户
     * @param string $username
     * @param string $password 明文注册密码
     * @param int $agent_id 在哪个代理下注册
     * @param boolean $auth_mobile 是否已经认证过手机号
     * @return Result
     */
    public static function initUser($username,$password,$agent_id,$auth_mobile = true)
    {
        $user = Db::name("users")->where("username = ? AND agent_id = ?",[$username,$agent_id])->field("id")->find();
        if($user)
        {
            return new Result(201, [], '用户已经存在!');
        }else{
            $salt = mt_rand(100000, 999999);
            $userData = [
                'username'=>$username,
                'salt'=>$salt,
                'password'=>self::makePassword($password, $salt),
                'nickname'=>'用户'.substr($username, -4),
                'reg_time'=>time(),
                'agent_id'=>$agent_id,
                'mid'=>1,
                'ad_mid'=>1,
                'ip'=>get_client_ip(),
                'auth_mobile'=>$auth_mobile ? 1 : 0,
                'mobile'=>$auth_mobile ? $username : ''
            ];
            $recommend = self::getRecommendLogByMobile($username);
            $recommendUser = false;
            if($recommend)
            {
                $recommendUser = self::get($recommend['invite_user_id']);
                if($recommendUser)
                {
                    $userData['mid'] = $recommendUser['mid'];
                    $userData['ad_mid'] = $recommendUser['ad_mid'];
                    $userData['agent_id'] = $recommendUser['agent_id'];
                    $userData['parent_uid'] = $recommendUser['id'];
                    $userData['salesman_id'] = $recommendUser['salesman_id'];
                }
            }
            $uid = Db::name("users")->insertGetId($userData);
            if($uid)
            {
                //正常账号密码登录 不需要初始化用户微信表
//                 $userwx = [
//                     'user_id'=>$uid,
//                     'app_openid'=>'',
//                     'app_unionid'=>'',
//                     'recommend_userid'=>$recommendUser ? $recommendUser['id'] : 0,
//                     'created'=>time()
//                 ];
//                 Db::name("user_wx")->insertGetId($userwx);
                //发送红包
                $redpack = new UserRedpackage();
                $redpack->setTitle('系统红包');
                $redpack->setRemark('注册有礼送红包!');
                $redpack->setRed_class('reg');
            
                $redpack->sendRedPack($uid, Config::getConfig("reg_money"), Config::getConfig("reg_redpacknum"));
                if($recommendUser)
                {
                    $recommendMoney = Config::getConfig('recommend_regmoney');
            
                    if($recommendMoney)
                    {
                        $recommendMoney = explode(',', $recommendMoney);
                        if(count($recommendMoney))
                        {
                            $redpack->setTitle('好友红包');
                            $redpack->setRemark($recommendUser['nickname'].' 送您一个大红包');
                            $redpack->setApp_pop(1);
                            $redpack->setRed_class('recommend');
                            $redpack->setRed_class_param($recommendUser['id']);
                            //发送推荐红包？
                            $redpack->sendRedPack($uid, $recommendMoney, 1);
                        }
                    }
                }
                $row = Db::name("users")->where("id = ?",[$uid])->find();
                return new Result(200, $row, '');
            }else{
                return new Result(201, [], '新增用户数据失败!');
            }
        }
    }
    
    /**
     * 初始化微信 用户
     * @param array $wxinfo
     */
    public static function initUserByWx($wxinfo,$agent_id)
    {
        $user = Db::name("user_wx")->where("app_unionid = ? AND agent_id = ?",[$wxinfo['unionid'],$agent_id])->field("user_id")->find();
        if($user)
        {
            return new Result(201, [], '用户已经存在!');
        }
        //
        $salt = mt_rand(100000, 999999);
        $password = mt_rand(100000, 999999);
        $userData = [
            'username'=>'wx'.time(),
            'salt'=>$salt,
            'password'=>self::makePassword($password, $salt),
            'nickname'=>$wxinfo['nickname'],
            'reg_time'=>time(),
            'agent_id'=>$agent_id,
            'mid'=>1,
            'ad_mid'=>1,
            'ip'=>get_client_ip(),
            'header_ico'=>$wxinfo['headimgurl'],
            'bind_wx'=>1
        ];
        $recommend = self::getRecommendLogByWx($wxinfo['unionid']);
        $recommendUser = false;
        if($recommend)
        {
            $recommendUser = self::get($recommend['invite_user_id']);
            if($recommendUser)
            {
                $userData['mid'] = $recommendUser['mid'];
                $userData['ad_mid'] = $recommendUser['ad_mid'];
                $userData['agent_id'] = $recommendUser['agent_id'];
                $userData['parent_uid'] = $recommendUser['id'];
                $userData['salesman_id'] = $recommendUser['salesman_id'];
            }
        }
        $uid = Db::name("users")->insertGetId($userData);
        if($uid)
        {
                $userwx = [
                    'user_id'=>$uid,
                    'app_openid'=>$wxinfo['openid'],
                    'app_unionid'=>$wxinfo['unionid'],
                    'recommend_userid'=>$recommendUser ? $recommendUser['id'] : 0,
                    'created'=>time()
                ];
                Db::name("user_wx")->insertGetId($userwx);
                //发送红包
                $redpack = new UserRedpackage();
                $redpack->setTitle('系统红包');
                $redpack->setRemark('注册有礼送红包!');
                $redpack->setRed_class('reg');
                
                $redpack->sendRedPack($uid, Config::getConfig("reg_money"), Config::getConfig("reg_redpacknum"));
                if($recommendUser)
                {
                    $recommendMoney = Config::getConfig('recommend_regmoney');
                    
                    if($recommendMoney)
                    {
                        $recommendMoney = explode(',', $recommendMoney);
                        if(count($recommendMoney))
                        {
                            $redpack->setTitle('好友红包');
                            $redpack->setRemark($recommendUser['nickname'].' 送您一个大红包');
                            $redpack->setApp_pop(1);
                            $redpack->setRed_class('recommend');
                            $redpack->setRed_class_param($recommendUser['id']);
                            //发送推荐红包？
                            $redpack->sendRedPack($uid, $recommendMoney, 1);
                        }
                    }
                }
                $row = Db::name("users")->where("id = ?",[$uid])->find();
                return new Result(200, $row, '');
        }else{
            return new Result(201, [], '新增用户数据失败!');
        }
    }
    /**
     * 查找推荐数据
     * @param string $wx_unionid
     */
    public static function getRecommendLogByWx($wx_unionid)
    {
        $row = Db::name("recommend_log")->where("wx_unionid = ?",[$wx_unionid])->find();
        return $row;
    }
    /**
     * 查找推荐数据
     * @param string $mobile
     */
    public static function getRecommendLogByMobile($mobile)
    {
        $row = Db::name("recommend_log")->where("mobile = ?",[$mobile])->find();
        return $row;
    }
    /**
     * 生成密码
     * @param unknown $password
     * @param unknown $salt
     */
    public static function makePassword($password,$salt)
    {
        return md5(md5($password).$salt);
    }

    /**
     * 获取团队
     * @param  int $user_id  查询的用户ID
     * @param  int $level    查询的级别 
     * @param  int $run      此参数不传
     * @return array         
     */
    public static function get_level_team($user_id,$level=1,$run=1){
      $user_id=(array)$user_id;
      $childs=Db::name('users')->field('id','parent_uid')->where('parent_uid','in',$user_id)->select();
      if($level==$run || !$childs){
        return $childs;
      }
      $run=$run+1;
      $ids=[];
      foreach ($childs as $k => $v) {
        $ids[]=$v['id'];
      }
      return self::get_level_team($ids,$level,$run);
    }
    /**
     * 淘宝快速注册
     * @param string $nickname
     * @param string $avatar_url
     * @param string $openid
     * @param string $opensid
     * @param string $top_accesstoken
     * @param int $agent_id
     */
    public static function initUserByTb($nickname,$avatar_url,$openid,$opensid,$top_accesstoken,$agent_id)
    {
        $isJoin = Db::name("users_taobao_auth")->where("opensid = ? AND agent_id = ?",$opensid,$agent_id)->field('id')->find();
        if($isJoin)
        {
            return new Result(201, [], '账号已经存在');
        }else{
            $salt = mt_rand(100000, 999999);
            $password = mt_rand(100000, 999999);
            $userData = [
                'username'=>'tb'.time(),
                'salt'=>$salt,
                'password'=>self::makePassword($password, $salt),
                'nickname'=>$nickname,
                'reg_time'=>time(),
                'agent_id'=>$agent_id,
                'mid'=>1,
                'ad_mid'=>1,
                'ip'=>get_client_ip(),
                'header_ico'=>$avatar_url,
                'tbauth'=>1
            ];
            //TODO 暂时不用推荐功能
            $uid = Db::name("users")->insertGetId($userData);
            if($uid)
            {
                $userwx = [
                    'agent_id'=>$agent_id,
                    'user_id'=>$uid,
                    'nickname'=>$nickname,
                    'avatar_url'=>$avatar_url,
                    'openid'=>$openid,
                    'opensid'=>$opensid,
                    'top_accesstoken'=>$top_accesstoken,
                    'created'=>time()
                ];
                Db::name("users_taobao_auth")->insertGetId($userwx);
            }
            $row = Db::name("users")->where("id = ?",[$uid])->find();
            return new Result(200, $row, '');
        }
    }
}
