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
/**
 * API action控制器
 * @package app\api\controller
 */
class pushbind extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['channel_id'=>'请传入初始化后的channel_id','sdk_userid'=>'请传入初始化后的sdk_userid','os'=>'请选择手机系统!']);
      $channel_id = $this->getRequest('channel_id','');
      $sdk_userid = $this->getRequest('sdk_userid','');
      $os = $this->getRequest('os','');
      if(!$channel_id || !$sdk_userid)
      {
          $this->response([], 202, '请上传完整参数!');
      }
      if(!in_array($os, ['ios','android']))
      {
          $this->response([], 201, '错误的系统!');
      }
      
      $r = $this->isAppLogin(true);
      if($r->isSuccess())
      {
          $agent_id = AdminClientkey::getAgentId($this->getAppid());
          $os = $this->getRequest('os','');
          $row = Db::name("admin_push_config")->where("agent_id = ? AND os = ?",[$agent_id,$os])->find();
          if($row)
          {
              //1.查找是否存在记录 不存在 新增，否则更新
              $log = Db::name("baidu_push_bind")->where("user_id = ? AND apikey = ?",[$this->getUserId(),$row['apikey']])->find();
              if($log)
              {
                  Db::name("baidu_push_bind")->where('id',$log['id'])->update(['bind_time'=>date('Y-m-d H:i:s'),'channel_id'=>$channel_id,'sdk_userid'=>$sdk_userid]);
              }else{
                  $user = $r->getData();
                  $data = [
                      'apikey'=>$row['apikey'],
                      'user_id'=>$this->getUserId(),
                      'username'=>$user['username'],
                      'channel_id'=>$channel_id,
                      'sdk_userid'=>$sdk_userid,
                      'os'=>$os,
                      'bind_time'=>date('Y-m-d H:i:s')
                  ];
                  Db::name("baidu_push_bind")->insert($data);
              }
              $this->response([], 200, '');
          }else{
              $this->response([], 201, '没有找到推送配置!');
          }
      }else{
          $this->response([], $this->_notLoginCode  , '');
      }
    }
}