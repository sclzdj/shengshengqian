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

use think\Db;
use app\common\model\AdminClientkey;
/**
 * API action控制器
 * @package app\api\controller
 */
class sharegetconfig extends Baseaction
{
    function run()
    {
      //输入你的代码
      $appid = $this->getAppid();
      $agent_id = AdminClientkey::getAgentId($appid);
      $row = Db::name("admin_share_config")->where("agent_id = ?",[$agent_id])->find();
      if(!$row)
      {
          $this->response([], 202, 'not set');
      }else{
          $this->response($row, 200, '');
      }
    }
}