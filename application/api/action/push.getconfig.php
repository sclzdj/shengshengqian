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
class pushgetconfig extends Baseaction
{
    function run()
    {
      //输入你的代码 不限定登录
      $this->checkNeedParam(['package'=>'请输入包名!','os'=>'请输入手机系统格式!']);
      $agent_id = AdminClientkey::getAgentId($this->getAppid());
      $os = $this->getRequest('os','');
      $package = $this->getRequest('package','');
      if(!in_array($os, ['ios','android']))
      {
          $this->response([], 201, '错误的系统!');
      }
      $row = Db::name("admin_push_config")->where("agent_id = ? AND os = ? AND package_name = ?",[$agent_id,$os,$package])->find();
      if($row)
      {
          $result = [
              'apikey'=>$row['apikey']
          ];
          $this->response($result, 200, '');
      }else{
          $this->response([], 202, '没有配置推送!');
      }
    }
}